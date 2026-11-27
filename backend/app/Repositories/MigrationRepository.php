<?php

namespace App\Repositories;

use Illuminate\Database\Migrations\Migrator;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

final class MigrationRepository
{
    public const BUSY = 75;

    public function __construct(private Migrator $migrator) {}

    public function status(): array
    {
        return $this->migrator->usingConnection(DB::getDefaultConnection(), function () {
            $hasHistory = $this->migrator->repositoryExists();
            $batches = $hasHistory ? $this->migrator->getRepository()->getMigrationBatches() : [];
            $migrations = [];
            foreach ($this->migrator->getMigrationFiles([$this->path()]) as $name => $file) {
                $migrations[] = ['name' => $name, 'batch' => isset($batches[$name]) ? (int) $batches[$name] : null,
                    'status' => isset($batches[$name]) ? 'ran' : 'pending'];
            }

            return ['database' => DB::connection()->getDatabaseName(), 'environment' => app()->environment(),
                'hasHistory' => $hasHistory, 'migrations' => $migrations];
        });
    }

    public function migrate(): int
    {
        return Artisan::call('migrate', [
            '--path' => [$this->path()], '--realpath' => true, '--force' => true,
            '--no-interaction' => true, '--isolated' => self::BUSY,
        ]);
    }

    private function path(): string
    {
        return database_path('migrations');
    }
}
