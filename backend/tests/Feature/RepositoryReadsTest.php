<?php

namespace Tests\Feature;

use App\Repositories\BookingCriteria;
use App\Repositories\BookingRepository;
use App\Repositories\IntakeCriteria;
use App\Repositories\ReadinessIntakeRepository;
use App\Repositories\ReadPage;
use DateTimeImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

final class RepositoryReadsTest extends TestCase
{
    use RefreshDatabase;

    public function test_booking_reader_handles_one_many_and_no_matches(): void
    {
        $later = $this->booking('B', '2030-01-03 10:00:00', 'readiness', 'family');
        $first = $this->booking('A', '2030-01-02 10:00:00', 'readiness', 'family');
        $other = $this->booking('C', '2030-01-03 10:15:00', 'readiness', 'business');
        $this->booking('D', '2030-01-03 10:30:00', 'financial', 'family');
        $bookings = app(BookingRepository::class);

        $this->assertSame([$first], $bookings->findBookings(new BookingCriteria(id: $first))->pluck('id')->all());
        $this->assertSame($later, $bookings->findBookings(new BookingCriteria(reference: str_repeat('B', 24)))->first()->id);
        $this->assertTrue($bookings->referenceExists(str_repeat('B', 24)));
        $this->assertFalse($bookings->referenceExists(str_repeat('E', 24)));
        $this->assertNull($bookings->findBookings(new BookingCriteria(id: $first, service: 'financial'))->first());
        $this->assertSame([$first, $later], $bookings->findBookings(new BookingCriteria(
            service: 'readiness',
            subtype: 'family',
            startsFrom: new DateTimeImmutable('2030-01-02T05:00:00-05:00'),
            startsThrough: new DateTimeImmutable('2030-01-03T18:00:00+08:00'),
        ))->pluck('id')->all());
        $this->assertSame([$first, $later], $bookings->findBookings(page: new ReadPage(size: 2))->pluck('id')->all());
        $this->assertSame([$other], $bookings->findBookings(page: new ReadPage(number: 3, size: 1))->pluck('id')->all());
        $this->assertSame(['2030-01-03T10:45:00Z'], $bookings->available([
            '2030-01-03T10:00:00Z', '2030-01-03T10:15:00Z', '2030-01-03T10:30:00Z', '2030-01-03T10:45:00Z',
        ]));
    }

    public function test_intake_reader_pages_summaries_and_only_selects_details_on_request(): void
    {
        $intakes = app(ReadinessIntakeRepository::class);
        $intakes->create('first-fixture', 'sealed-answers', 'sealed-files');
        $intakes->create('newest-fixture', 'newest-sealed-answers', 'newest-sealed-files');

        $this->assertTrue($intakes->exists('first-fixture'));
        $this->assertFalse($intakes->exists('missing-fixture'));
        $summary = $intakes->findIntakes(page: new ReadPage(size: 1))->sole();
        $this->assertSame('newest-fixture', $summary->appointment_ref);
        $this->assertSame(['id', 'appointment_ref', 'created_at'], array_keys((array) $summary));
        $older = $intakes->findIntakes(page: new ReadPage(number: 2, size: 1))->sole();
        $this->assertSame('first-fixture', $older->appointment_ref);
        $this->assertSame($older->id, $intakes->findIntakes(new IntakeCriteria(reference: 'first-fixture'))->sole()->id);
        $details = $intakes->findIntakes(new IntakeCriteria(id: $older->id), withDetails: true)->sole();
        $this->assertSame('sealed-answers', $details->answers);
        $this->assertSame('sealed-files', $details->files);
        $this->assertTrue($intakes->findIntakes(new IntakeCriteria(id: $older->id, reference: 'newest-fixture'))->isEmpty());
    }

    #[DataProvider('invalidPages')]
    public function test_read_pages_reject_invalid_bounds(int $page, int $size): void
    {
        $this->expectException(InvalidArgumentException::class);
        new ReadPage($page, $size);
    }

    public static function invalidPages(): array
    {
        return [[0, 50], [1, 0], [1, 101], [PHP_INT_MAX, 100]];
    }

    private function booking(string $reference, string $start, string $service, string $subtype): int
    {
        return DB::table('phelon_discovery_bookings')->insertGetId([
            'reference' => str_repeat($reference, 24),
            'starts_at_utc' => $start,
            'ends_at_utc' => (new DateTimeImmutable($start))->modify('+15 minutes')->format('Y-m-d H:i:s'),
            'service_key' => $service,
            'subtype_key' => $subtype,
            'name' => 'Synthetic visitor',
            'email' => 'visitor@example.test',
            'phone' => '212-555-0170',
            'state_code' => 'NY',
            'visitor_timezone' => 'UTC',
            'concern' => 'Fixture only',
        ]);
    }
}
