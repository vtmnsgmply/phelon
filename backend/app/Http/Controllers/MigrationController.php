<?php

namespace App\Http\Controllers;

use App\Http\Requests\RunMigrationsRequest;
use App\Services\MigrationService;

final class MigrationController extends Controller
{
    public function index(MigrationService $service): array
    {
        return $service->status();
    }

    public function run(RunMigrationsRequest $request, MigrationService $service): array
    {
        return $service->run($request->user());
    }
}
