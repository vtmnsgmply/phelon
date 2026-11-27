<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookDiscovery;
use App\Http\Requests\BookingAvailabilityRequest;
use App\Services\BookingService;
use Illuminate\Http\JsonResponse;

final class BookingController extends Controller
{
    public function availability(BookingAvailabilityRequest $request, BookingService $service): array
    {
        return $service->availability($request->validated());
    }

    public function reserve(BookDiscovery $request, BookingService $service): JsonResponse
    {
        return $service->reserve($request->validated());
    }

    public function upcoming(BookingService $service): array
    {
        return $service->upcoming();
    }
}
