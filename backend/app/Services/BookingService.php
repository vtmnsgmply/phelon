<?php

namespace App\Services;

use App\Repositories\BookingCriteria;
use App\Repositories\BookingRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

final class BookingService
{
    public function __construct(
        private BookingRepository $bookings,
        private ServiceCatalog $catalog,
    ) {}

    public function availability(array $input): array
    {
        $schedule = new BookingSchedule();
        $bounds = $schedule->bounds();
        try {
            return $bounds + ['slots' => $this->bookings->available($schedule->slots($input['date'] ?? $bounds['minDate']))];
        } catch (QueryException) {
            abort(503, 'Booking availability is temporarily unavailable.');
        }
    }

    public function reserve(array $input): JsonResponse
    {
        $selection = $this->catalog->resolve($input['interest'] ?? null);
        if ($selection === null || !in_array($input['start'], (new BookingSchedule())->slots($input['date']), true)) {
            throw ValidationException::withMessages(['start' => 'Choose a valid service and appointment time.']);
        }
        try {
            $reference = $this->bookings->create($input, $selection);
        } catch (QueryException) {
            abort(503, 'Booking is temporarily unavailable. No reservation was confirmed.');
        }
        return response()->json(['reference' => $reference, 'startsAt' => $input['start']], 201);
    }

    public function upcoming(): array
    {
        try {
            $items = $this->bookings->findBookings(new BookingCriteria(startsFrom: now('UTC')));
        } catch (QueryException) {
            abort(503, 'The booking list is temporarily unavailable.');
        }
        foreach ($items as $item) {
            $item->startsAt = str_replace(' ', 'T', $item->starts_at_utc).'Z';
            unset($item->id, $item->starts_at_utc);
            $item->serviceTitle = $this->catalog->label($item->service_key, $item->subtype_key);
        }
        return ['bookings' => $items->all()];
    }
}
