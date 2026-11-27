<?php
declare(strict_types=1);
require dirname(__DIR__) . '/app/bootstrap.php';
require dirname(__DIR__) . '/components/discovery-calendar.php';

use Phelon\Booking\{BookingRepository, BookingRequest, Schedule};
use Phelon\Core\{Api, Database, HttpError, RateLimiter, Session};

Api::run(static function (string $method): array {
    if (!in_array($method, ['GET', 'POST'], true)) {
        header('Allow: GET, POST');
        throw new HttpError(405, 'Method not allowed.');
    }
    Session::start();
    if ($method === 'GET') {
        $schedule = new Schedule();
        $bounds = $schedule->bounds();
        $date = $_GET['date'] ?? null;
        if ($date !== null && !is_string($date)) {
            throw new HttpError(422, 'Choose a valid date.');
        }
        $token = Session::token();
        session_write_close();
        $repository = new BookingRepository(Database::connect());
        return [200, $bounds + ['csrfToken' => $token,
            'slots' => $repository->available($schedule->slots($date ?? $bounds['minDate']))]];
    }

    Session::verify();
    session_write_close();
    $booking = BookingRequest::validate(Api::json());
    $schedule = new Schedule();
    if (!in_array($booking['start'], $schedule->slots($booking['date']), true)) {
        throw new HttpError(422, 'Choose an available appointment time.');
    }
    $pdo = Database::connect();
    (new RateLimiter($pdo))->hit('booking/ip/' . ($_SERVER['REMOTE_ADDR'] ?? ''), 12);
    $reference = (new BookingRepository($pdo))->insert($booking);
    return [201, ['reference' => $reference, 'startsAt' => $booking['start']]];
});
