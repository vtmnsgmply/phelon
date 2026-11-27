<?php
declare(strict_types=1);

$target = getenv('DB_DATABASE') ?: '';
if (PHP_SAPI !== 'cli' || getenv('PHELON_TEST_MODE') !== '1' || getenv('DB_CONNECTION') !== 'sqlite'
    || basename($target) !== 'fixture.sqlite' || !str_starts_with(basename(dirname($target)), 'phelon-react-test-')) {
    exit(1);
}
require dirname(__DIR__, 2).'/vendor/autoload.php';
$app = require dirname(__DIR__, 2).'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();
\App\Models\StaffUser::create(['email' => 'staff@example.test', 'display_name' => 'Fixture Staff',
    'password_hash' => \Illuminate\Support\Facades\Hash::make('Fixture-staff-secret'), 'active' => true]);
