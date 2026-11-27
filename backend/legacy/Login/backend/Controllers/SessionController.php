<?php
declare(strict_types=1);

namespace Phelon\Login\Controllers;

use Phelon\Core\{Api, Database, HttpError, RateLimiter, Session};
use Phelon\Login\Repositories\StaffRepository;
use Phelon\Login\Requests\LoginRequest;
use Phelon\Login\Services\SessionService;

final class SessionController
{
    public function handle(string $method): array
    {
        if (!in_array($method, ['GET', 'POST', 'DELETE'], true)) {
            header('Allow: GET, POST, DELETE');
            throw new HttpError(405, 'Method not allowed.');
        }
        Session::start();
        if ($method === 'GET' && !isset($_SESSION['staff_id'])) {
            return [200, SessionService::response(null)];
        }
        if ($method !== 'GET') {
            Session::verify();
        }
        if ($method === 'DELETE') {
            return [200, SessionService::logout()];
        }
        $request = $method === 'POST' ? LoginRequest::from(Api::json()) : null;
        $pdo = Database::connect();
        $service = new SessionService(new StaffRepository($pdo), new RateLimiter($pdo));
        return [200, $request ? $service->login($request, $_SERVER['REMOTE_ADDR'] ?? '') : $service->current()];
    }
}
