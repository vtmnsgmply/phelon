<?php

namespace Tests\Unit;

use FilesystemIterator;
use PHPUnit\Framework\TestCase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;
use ReflectionNamedType;

final class ThinControllersTest extends TestCase
{
    public function test_controller_actions_only_delegate_in_at_most_two_lines(): void
    {
        $root = dirname(__DIR__, 2).'/app/Http/Controllers';
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS));
        $checked = 0;

        foreach ($files as $file) {
            if (!$file->isFile() || $file->getExtension() !== 'php') {
                continue;
            }
            $relative = substr($file->getPathname(), strlen($root) + 1, -4);
            $controller = new ReflectionClass('App\\Http\\Controllers\\'.str_replace(['/', '\\'], '\\', $relative));
            $lines = file($file->getPathname(), FILE_IGNORE_NEW_LINES);

            foreach ($controller->getMethods() as $method) {
                if ($method->getDeclaringClass()->getName() !== $controller->getName() || $method->isAbstract()) {
                    continue;
                }
                $action = $controller->getShortName().'::'.$method->getName();
                $source = implode("\n", array_slice($lines, $method->getStartLine() - 1, $method->getEndLine() - $method->getStartLine() + 1));
                $start = strpos($source, '{');
                $end = strrpos($source, '}');
                $this->assertIsInt($start, $action);
                $this->assertIsInt($end, $action);
                $body = trim(substr($source, $start + 1, $end - $start - 1));
                if ($method->isConstructor()) {
                    $this->assertSame('', $body, $action.' must not run initialization logic.');
                    continue;
                }

                $this->assertLessThanOrEqual(2, substr_count($body, "\n") + 1, $action.' exceeds two body lines.');
                $this->assertSame(1, preg_match('/\Areturn\s+(\$this->|\$)(\w+)->\w+\([^;{}]*\);\z/s', $body, $call), $action.' must only return one delegation.');
                $type = null;
                if ($call[1] === '$this->' && $controller->hasProperty($call[2])) {
                    $type = $controller->getProperty($call[2])->getType();
                } elseif ($call[1] === '$') {
                    foreach ($method->getParameters() as $parameter) {
                        if ($parameter->getName() === $call[2]) {
                            $type = $parameter->getType();
                            break;
                        }
                    }
                }
                $this->assertInstanceOf(ReflectionNamedType::class, $type, $action.' must delegate to a typed dependency.');
                $this->assertMatchesRegularExpression('/\AApp\\\\(?:Services|Http\\\\Requests)\\\\/', $type->getName(), $action.' must delegate to a service or Form Request.');
                $checked++;
            }
        }

        $this->assertGreaterThan(0, $checked);
    }
}
