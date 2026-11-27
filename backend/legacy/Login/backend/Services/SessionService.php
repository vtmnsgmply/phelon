<?php
declare(strict_types=1);

namespace Phelon\Login\Services;

use Phelon\Core\{HttpError, RateLimiter, Session};
use Phelon\Login\Repositories\StaffRepository;
use Phelon\Login\Requests\LoginRequest;

final class SessionService
{
    private const DUMMY_HASH = '$2y$12$jjS86naUJwrbvZsM6bLO7eFmFrUciHfjGIg/Dxrudjp43J3djLguC';

    public function __construct(private StaffRepository $staff, private RateLimiter $limits) {}

    public function login(LoginRequest $request, string $ip): array
    {
        $this->limits->hit('login/ip/' . $ip, 30);
        $this->limits->hit('login/account/' . $ip . '/' . $request->email, 8);
        $person = $this->staff->byEmail($request->email);
        $valid = password_verify($request->password, $person['password_hash'] ?? self::DUMMY_HASH);
        if (!$valid || !$person || (int) $person['active'] !== 1) {
            throw new HttpError(401, 'Email or password was incorrect.');
        }
        Session::rotate();
        $_SESSION['staff_id'] = (int) $person['id'];
        $_SESSION['signed_at'] = $_SESSION['last_seen'] = time();
        return self::response($person);
    }

    public function current(): array
    {
        $now = time();
        $person = null;
        if ($now - ($_SESSION['last_seen'] ?? 0) <= 1800 && $now - ($_SESSION['signed_at'] ?? 0) <= 28800) {
            $person = $this->staff->activeById((int) $_SESSION['staff_id']);
        }
        if (!$person) {
            return self::logout();
        }
        $_SESSION['last_seen'] = $now;
        return self::response($person);
    }

    public static function logout(): array
    {
        unset($_SESSION['staff_id'], $_SESSION['signed_at'], $_SESSION['last_seen']);
        Session::rotate();
        return self::response(null);
    }

    public static function response(?array $person): array
    {
        return [
            'user' => $person ? ['displayName' => $person['display_name']] : null,
            'csrfToken' => Session::token(),
        ];
    }
}
