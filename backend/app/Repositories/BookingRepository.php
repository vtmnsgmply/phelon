<?php

namespace App\Repositories;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final class BookingRepository
{
    private const TABLE = 'phelon_discovery_bookings';

    private static function sqlDate(string $instant): string
    {
        return str_replace('T', ' ', substr($instant, 0, 19));
    }

    public function available(array $slots): array
    {
        if (!$slots) {
            return [];
        }
        $reserved = $this->query(new BookingCriteria(
            startsFrom: new DateTimeImmutable($slots[0]),
            startsThrough: new DateTimeImmutable($slots[array_key_last($slots)]),
        ))->pluck('starts_at_utc')->flip();
        return array_values(array_filter($slots, fn (string $slot) => !$reserved->has(self::sqlDate($slot))));
    }

    public function create(array $input, array $selection): string
    {
        $reference = strtoupper(bin2hex(random_bytes(12)));
        try {
            DB::table(self::TABLE)->insert([
                'reference' => $reference,
                'starts_at_utc' => self::sqlDate($input['start']),
                'ends_at_utc' => gmdate('Y-m-d H:i:s', strtotime($input['start']) + 900),
                'service_key' => $selection[0],
                'subtype_key' => $selection[1],
                'name' => $input['name'],
                'email' => $input['email'],
                'phone' => $input['phone'],
                'state_code' => $input['state'],
                'visitor_timezone' => $input['timezone'],
                'concern' => $input['concern'],
            ]);
        } catch (UniqueConstraintViolationException) {
            abort(409, 'That time is no longer available. Choose another time.');
        }
        return $reference;
    }

    /** @return Collection<int, \stdClass> */
    public function findBookings(BookingCriteria $criteria = new BookingCriteria(), ReadPage $page = new ReadPage()): Collection
    {
        return $page->apply($this->query($criteria)->orderBy('starts_at_utc'))
            ->get(['id', 'reference', 'starts_at_utc', 'service_key', 'subtype_key', 'name', 'email', 'phone', 'visitor_timezone']);
    }

    public function referenceExists(string $reference): bool
    {
        return $this->query(new BookingCriteria(reference: $reference))->exists();
    }

    private function query(BookingCriteria $criteria): Builder
    {
        $query = DB::table(self::TABLE);
        foreach (['id' => $criteria->id, 'reference' => $criteria->reference,
            'service_key' => $criteria->service, 'subtype_key' => $criteria->subtype] as $column => $value) {
            if ($value !== null) {
                $query->where($column, $value);
            }
        }
        if ($criteria->startsFrom !== null) {
            $query->where('starts_at_utc', '>=', self::utcDate($criteria->startsFrom));
        }
        if ($criteria->startsThrough !== null) {
            $query->where('starts_at_utc', '<=', self::utcDate($criteria->startsThrough));
        }
        return $query;
    }

    private static function utcDate(DateTimeInterface $instant): string
    {
        return DateTimeImmutable::createFromInterface($instant)->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d H:i:s');
    }
}
