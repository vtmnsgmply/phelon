<?php

namespace App\Services;

use DateTimeImmutable;
use DateTimeZone;
use Illuminate\Validation\ValidationException;

final class BookingSchedule
{
    public const DURATION = 15;
    private DateTimeZone $zone;
    private DateTimeImmutable $now;
    private string $open;
    private string $close;
    private array $days;

    public function __construct()
    {
        $settings = config('phelon.booking');
        $zone = (string) $settings['timezone'];
        $this->open = (string) $settings['open'];
        $this->close = (string) $settings['close'];
        $days = (string) $settings['days'];
        $quarterHour = '/\A(?:[01][0-9]|2[0-3]):(?:00|15|30|45)\z/';
        if (!in_array($zone, DateTimeZone::listIdentifiers(), true) || !preg_match($quarterHour, $this->open)
            || !preg_match($quarterHour, $this->close) || $this->close <= $this->open
            || !preg_match('/\A[1-7](?:,[1-7]){0,6}\z/', $days)) {
            abort(503, 'Online scheduling is not available yet. Please contact the team for help.');
        }
        $this->days = explode(',', $days);
        $this->zone = new DateTimeZone($zone);
        $this->now = new DateTimeImmutable('now', $this->zone);
    }

    public function bounds(): array
    {
        return [
            'minDate' => $this->now->format('Y-m-d'),
            'maxDate' => $this->now->modify('+45 days')->format('Y-m-d'),
            'timezone' => $this->zone->getName(),
            'durationMinutes' => self::DURATION,
        ];
    }

    public function slots(string $date): array
    {
        $bounds = $this->bounds();
        if ($date < $bounds['minDate'] || $date > $bounds['maxDate']) {
            throw ValidationException::withMessages(['date' => 'Choose a date within the booking window.']);
        }
        $open = new DateTimeImmutable($date.' '.$this->open, $this->zone);
        $close = new DateTimeImmutable($date.' '.$this->close, $this->zone);
        if (!in_array($open->format('N'), $this->days, true)) {
            return [];
        }
        $earliest = $this->now->getTimestamp() + 3600;
        $slots = [];
        for ($time = $open->getTimestamp(); $time + 900 <= $close->getTimestamp(); $time += 900) {
            if ($time >= $earliest) {
                $slots[] = gmdate('Y-m-d\TH:i:s\Z', $time);
            }
        }
        return $slots;
    }
}
