<?php
declare(strict_types=1);

namespace Phelon\Booking;

use DateTimeImmutable;
use DateTimeZone;
use Phelon\Core\Config;
use Phelon\Core\HttpError;

final class Schedule
{
    public const DURATION = 15;
    private const HORIZON = 45;
    private DateTimeZone $zone;
    private string $open;
    private string $close;
    private array $days;
    private DateTimeImmutable $now;

    public function __construct(?DateTimeImmutable $now = null)
    {
        $zone = Config::get('PHELON_BOOKING_TIMEZONE');
        $this->open = Config::get('PHELON_BOOKING_OPEN');
        $this->close = Config::get('PHELON_BOOKING_CLOSE');
        $days = Config::get('PHELON_BOOKING_DAYS');
        if (!in_array($zone, DateTimeZone::listIdentifiers(), true)
            || !preg_match('/\A(?:[01][0-9]|2[0-3]):(?:00|15|30|45)\z/', $this->open)
            || !preg_match('/\A(?:[01][0-9]|2[0-3]):(?:00|15|30|45)\z/', $this->close)
            || $this->close <= $this->open || !preg_match('/\A[1-7](?:,[1-7]){0,6}\z/', $days)) {
            throw new HttpError(503, 'Online scheduling is not available yet. Please contact us for help.');
        }
        $this->zone = new DateTimeZone($zone);
        $this->days = explode(',', $days);
        $this->now = ($now ?? new DateTimeImmutable('now'))->setTimezone($this->zone);
    }

    public function bounds(): array
    {
        return [
            'minDate' => $this->now->format('Y-m-d'),
            'maxDate' => $this->now->modify('+' . self::HORIZON . ' days')->format('Y-m-d'),
            'timezone' => $this->zone->getName(),
            'durationMinutes' => self::DURATION,
        ];
    }

    public function slots(string $date): array
    {
        $bounds = $this->bounds();
        if (!preg_match('/\A[0-9]{4}-[0-9]{2}-[0-9]{2}\z/', $date)) {
            throw new HttpError(422, 'Choose a date within the booking window.');
        }
        $day = DateTimeImmutable::createFromFormat('!Y-m-d', $date, $this->zone);
        if (!$day || $day->format('Y-m-d') !== $date || $date < $bounds['minDate'] || $date > $bounds['maxDate']) {
            throw new HttpError(422, 'Choose a date within the booking window.');
        }
        if (!in_array($day->format('N'), $this->days, true)) {
            return [];
        }
        $open = new DateTimeImmutable($date . ' ' . $this->open, $this->zone);
        $close = new DateTimeImmutable($date . ' ' . $this->close, $this->zone);
        $earliest = $this->now->getTimestamp() + 3600;
        $utc = new DateTimeZone('UTC');
        $slots = [];
        for ($time = $open->getTimestamp(); $time + 900 <= $close->getTimestamp(); $time += 900) {
            if ($time >= $earliest) {
                $slots[] = $open->setTimestamp($time)->setTimezone($utc)->format('Y-m-d\TH:i:s\Z');
            }
        }
        return $slots;
    }
}
