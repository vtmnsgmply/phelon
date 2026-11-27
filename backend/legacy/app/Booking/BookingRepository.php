<?php
declare(strict_types=1);

namespace Phelon\Booking;

use PDO;
use PDOException;
use Phelon\Core\HttpError;

final class BookingRepository
{
    public function __construct(private PDO $pdo) {}

    public function available(array $slots): array
    {
        if (!$slots) {
            return [];
        }
        $statement = $this->pdo->prepare('SELECT starts_at_utc FROM phelon_discovery_bookings
            WHERE starts_at_utc BETWEEN ? AND ?');
        $statement->execute([self::dbTime($slots[0]), self::dbTime($slots[array_key_last($slots)])]);
        $reserved = array_flip($statement->fetchAll(PDO::FETCH_COLUMN));
        return array_values(array_filter($slots, static fn(string $slot): bool => !isset($reserved[self::dbTime($slot)])));
    }

    public function insert(array $booking): string
    {
        $reference = strtoupper(bin2hex(random_bytes(12)));
        $end = (new \DateTimeImmutable($booking['start']))->modify('+15 minutes')->format('Y-m-d H:i:s');
        $statement = $this->pdo->prepare('INSERT INTO phelon_discovery_bookings
            (reference, starts_at_utc, ends_at_utc, service_key, subtype_key, name, email, phone,
             state_code, visitor_timezone, concern) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
        try {
            $statement->execute([$reference, self::dbTime($booking['start']), $end, $booking['service'],
                $booking['subtype'], $booking['name'], $booking['email'], $booking['phone'], $booking['state'],
                $booking['timezone'], $booking['concern']]);
        } catch (PDOException $error) {
            if (($error->errorInfo[1] ?? null) === 1062) {
                throw new HttpError(409, 'That time is no longer available. Choose another time.');
            }
            throw $error;
        }
        return $reference;
    }

    private static function dbTime(string $instant): string
    {
        return str_replace('T', ' ', substr($instant, 0, 19));
    }
}
