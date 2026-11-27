<?php

namespace App\Http\Controllers;

use App\Http\Requests\IntakeStatusRequest;
use App\Http\Requests\SubmitReadinessIntakeRequest;
use App\Services\ReadinessIntakeService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class ReadinessIntakeController extends Controller
{
    public function status(IntakeStatusRequest $request, ReadinessIntakeService $service): JsonResponse
    {
        return $service->status($request->validated('reference'));
    }

    public function submit(SubmitReadinessIntakeRequest $request, ReadinessIntakeService $service): JsonResponse
    {
        return $service->submit($request->validated());
    }

    public function index(ReadinessIntakeService $service): array
    {
        return $service->index();
    }

    public function show(int $id, ReadinessIntakeService $service): array
    {
        return $service->show($id);
    }

    public function attachment(int $id, int $file, ReadinessIntakeService $service): StreamedResponse
    {
        return $service->attachment($id, $file);
    }
}
