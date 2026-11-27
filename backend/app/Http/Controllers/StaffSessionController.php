<?php

namespace App\Http\Controllers;

use App\Http\Requests\StaffLoginRequest;
use App\Services\StaffSessionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class StaffSessionController extends Controller
{
    public function show(Request $request, StaffSessionService $service): JsonResponse
    {
        return $service->show($request->session());
    }

    public function login(StaffLoginRequest $request, StaffSessionService $service): JsonResponse
    {
        return $service->login($request->validated(), $request->session());
    }

    public function logout(Request $request, StaffSessionService $service): JsonResponse
    {
        return $service->logout($request->session());
    }
}
