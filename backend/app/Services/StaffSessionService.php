<?php

namespace App\Services;

use Illuminate\Contracts\Session\Session;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

final class StaffSessionService
{
    public function show(Session $session): JsonResponse
    {
        $person = Auth::user();
        if ($person && (!$person->active || time() - (int) $session->get('staff_signed_at') > 28800)) {
            return $this->logout($session);
        }
        return $this->sessionResponse($session, $person ? [
            'displayName' => $person->display_name,
            'canManageMigrations' => MigrationAccess::allows($person),
        ] : null);
    }

    public function login(array $input, Session $session): JsonResponse
    {
        if (strlen($input['password']) > 72 || str_contains($input['password'], "\0")) {
            abort(401, 'Email or password was incorrect.');
        }
        try {
            $valid = Auth::attempt([
                'email' => strtolower($input['email']),
                'password' => $input['password'],
                'active' => true,
            ]);
        } catch (QueryException) {
            abort(503, 'Staff sign-in is temporarily unavailable.');
        }
        abort_unless($valid, 401, 'Email or password was incorrect.');
        $session->regenerate();
        $session->put('staff_signed_at', time());
        return $this->show($session);
    }

    public function logout(Session $session): JsonResponse
    {
        Auth::logout();
        $session->invalidate();
        $session->regenerateToken();
        return $this->sessionResponse($session, null);
    }

    private function sessionResponse(Session $session, ?array $user): JsonResponse
    {
        return response()->json(['user' => $user, 'csrfToken' => $session->token()])->header('Cache-Control', 'no-store');
    }
}
