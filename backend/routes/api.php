<?php

use App\Http\Controllers\{BookingController, MigrationController, ReadinessIntakeController, ResourceLibraryController, StaffSessionController};
use App\Http\Middleware\RequireMigrationAdmin;
use App\Http\Middleware\RequireStaff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Laravel supplies the /api prefix. The first-party clients still use session cookies and CSRF.
Route::middleware('web')->group(function () {
    Route::get('context', fn (Request $request) => response()->json(['csrfToken' => $request->session()->token()])->header('Cache-Control', 'no-store'));
    Route::get('availability', [BookingController::class, 'availability'])->middleware('throttle:90,1');
    Route::post('bookings', [BookingController::class, 'reserve'])->middleware('throttle:12,15');
    Route::get('session', [StaffSessionController::class, 'show']);
    Route::post('session', [StaffSessionController::class, 'login'])->middleware('throttle:staff-login');
    Route::delete('session', [StaffSessionController::class, 'logout']);
    Route::get('intake/status', [ReadinessIntakeController::class, 'status'])->middleware('throttle:30,15');
    Route::post('intake', [ReadinessIntakeController::class, 'submit'])->middleware('throttle:8,15');
    Route::post('library/verify', [ResourceLibraryController::class, 'verify'])->middleware('throttle:6,15');
    Route::post('library/resend', [ResourceLibraryController::class, 'resend'])->middleware('throttle:3,15');
    Route::get('library/document', [ResourceLibraryController::class, 'document']);
    Route::get('checkout/{offer}', function (string $offer) {
        $url = config('phelon.checkout.'.$offer);
        abort_unless(is_string($url) && preg_match('~\Ahttps://link\.fastpaydirect\.com/payment-link/[A-Za-z0-9]+\z~', $url), 404,
            'Online checkout is not open for this offer. Please contact the team.');
        return ['url' => $url];
    })->where('offer', '47|497|3497');
    Route::middleware(RequireStaff::class)->prefix('staff')->group(function () {
        Route::get('bookings', [BookingController::class, 'upcoming']);
        Route::get('intakes', [ReadinessIntakeController::class, 'index']);
        Route::get('intakes/{id}', [ReadinessIntakeController::class, 'show'])->whereNumber('id');
        Route::get('intakes/{id}/files/{file}', [ReadinessIntakeController::class, 'attachment'])->whereNumber(['id', 'file']);
    });
    Route::middleware([RequireStaff::class, RequireMigrationAdmin::class])->prefix('admin/migrations')->group(function () {
        Route::get('/', [MigrationController::class, 'index']);
        Route::post('/run', [MigrationController::class, 'run'])->middleware('throttle:5,1');
    });
    Route::any('{path}', fn () => response()->json(['message' => 'API route not found.'], 404))->where('path', '.*');
});
