<?php

namespace App\Services;

use App\Models\StaffUser;
use App\Repositories\MigrationRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Throwable;

final class MigrationService
{
    public const LOCK = 'phelon:dashboard-migrations';

    public function __construct(private MigrationRepository $migrations) {}

    public function status(): array
    {
        try {
            return $this->migrations->status();
        } catch (Throwable $error) {
            report($error);
            abort(503, 'Migration status is temporarily unavailable.');
        }
    }

    public function run(StaffUser $actor): array
    {
        $lock = Cache::lock(self::LOCK, 3600);
        abort_unless($lock->get(), 409, 'Migrations are already running. Refresh the list in a moment.');

        try {
            $before = $this->status();
            abort_unless($before['hasHistory'], 409, 'Set up the migration history on the server before running migrations here.');
            $pending = array_column(array_filter($before['migrations'], fn (array $row) => $row['status'] === 'pending'), 'name');
            if (!$pending) {
                return $before + ['applied' => []];
            }

            Log::notice('Back-office migration run started.', ['staff_id' => $actor->id, 'migrations' => $pending]);
            try {
                $exit = $this->migrations->migrate();
            } catch (Throwable $error) {
                report($error);
                abort(500, 'The migration run failed. Review the updated list and server logs before retrying.');
            }
            abort_if($exit === MigrationRepository::BUSY, 409, 'Migrations are already running. Refresh the list in a moment.');
            abort_if($exit !== 0, 500, 'The migration run failed. Review the updated list and server logs before retrying.');

            $after = $this->status();
            $ran = array_column(array_filter($after['migrations'], fn (array $row) => $row['status'] === 'ran'), 'name');
            $applied = array_values(array_intersect($pending, $ran));
            Log::notice('Back-office migration run finished.', ['staff_id' => $actor->id, 'applied' => $applied]);

            return $after + ['applied' => $applied];
        } finally {
            $lock->release();
        }
    }
}
