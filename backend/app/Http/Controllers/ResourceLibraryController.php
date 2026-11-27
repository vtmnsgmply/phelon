<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResendPurchaseCodeRequest;
use App\Http\Requests\VerifyPurchaseCodeRequest;
use App\Services\ResourceLibraryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

final class ResourceLibraryController extends Controller
{
    public function verify(VerifyPurchaseCodeRequest $request, ResourceLibraryService $service): JsonResponse
    {
        return $service->verify($request->validated(), $request->session());
    }

    public function resend(ResendPurchaseCodeRequest $request, ResourceLibraryService $service): array
    {
        return $service->resend($request->validated('transaction'));
    }

    public function document(Request $request, ResourceLibraryService $service): BinaryFileResponse
    {
        return $service->document($request->session());
    }
}
