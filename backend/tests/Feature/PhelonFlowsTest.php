<?php

namespace Tests\Feature;

use App\Models\StaffUser;
use Carbon\CarbonImmutable;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

final class PhelonFlowsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config([
            'app.key' => 'base64:'.base64_encode(str_repeat('x', 32)),
            'phelon.booking' => ['timezone' => 'UTC', 'open' => '09:00', 'close' => '10:00', 'days' => '1,2,3,4,5,6,7'],
        ]);
    }

    private function staff(): StaffUser
    {
        return StaffUser::create(['email' => 'staff@example.test', 'display_name' => 'Fixture Staff',
            'active' => true, 'password_hash' => Hash::make('Fixture-staff-secret')]);
    }

    private function reservation(): array
    {
        $date = CarbonImmutable::tomorrow('UTC')->toDateString();
        return ['date' => $date, 'start' => $date.'T09:00:00Z', 'name' => 'Fixture Visitor',
            'email' => 'visitor@example.test', 'phone' => '212-555-0170', 'state' => 'NY', 'timezone' => 'UTC',
            'concern' => 'General readiness planning',
            'interest' => 'reputation-digital-presence/executive-reputation-business-continuity'];
    }

    public function test_booking_validates_and_persists_one_slot(): void
    {
        $booking = $this->reservation();
        $available = '/api/availability?date='.$booking['date'];
        $this->getJson($available)->assertOk()->assertJsonCount(4, 'slots')->assertJsonPath('durationMinutes', 15);
        $this->postJson('/api/bookings', array_replace($booking, ['interest' => 'financial-stability/unknown']))->assertUnprocessable();
        $this->postJson('/api/bookings', array_replace($booking, ['start' => $booking['date'].'T11:00:00Z']))->assertUnprocessable();
        $this->postJson('/api/bookings', $booking)->assertCreated()->assertJsonPath('startsAt', $booking['start']);
        $this->postJson('/api/bookings', $booking)->assertConflict();
        $this->getJson($available)->assertOk()->assertJsonCount(3, 'slots');
        $this->assertDatabaseHas('phelon_discovery_bookings', [
            'service_key' => 'reputation-digital-presence',
            'subtype_key' => 'executive-reputation-business-continuity',
            'ends_at_utc' => $booking['date'].' 09:15:00',
        ]);
        $this->actingAs($this->staff())->withSession(['staff_signed_at' => time()])
            ->getJson('/api/staff/bookings')->assertOk()
            ->assertJsonPath('bookings.0.serviceTitle', 'Executive Reputation & Business Continuity')
            ->assertJsonPath('bookings.0.startsAt', $booking['start'])
            ->assertJsonMissingPath('bookings.0.id')->assertJsonMissingPath('bookings.0.starts_at_utc');
    }

    public function test_booking_does_not_offer_unconfigured_hours(): void
    {
        config(['phelon.booking.open' => null]);
        $this->getJson('/api/availability')->assertServiceUnavailable();
    }

    public function test_staff_session_and_dashboard_are_not_public(): void
    {
        $staff = $this->staff();
        $this->getJson('/api/staff/bookings')->assertUnauthorized();
        $this->getJson('/api/session')->assertOk()->assertJsonPath('user', null);
        $this->postJson('/api/session', ['email' => $staff->email, 'password' => 'wrong'])->assertUnauthorized();
        $this->postJson('/api/session', ['email' => $staff->email, 'password' => 'Fixture-staff-secret'])
            ->assertOk()->assertJsonPath('user.displayName', 'Fixture Staff');
        $this->getJson('/api/staff/bookings')->assertOk()->assertJsonPath('bookings', []);
        $this->deleteJson('/api/session')->assertOk()->assertJsonPath('user', null);
        $this->getJson('/api/staff/bookings')->assertUnauthorized();
    }

    public function test_intake_is_private_and_once_per_appointment(): void
    {
        Storage::fake('local');
        $reference = $this->postJson('/api/bookings', $this->reservation())->assertCreated()->json('reference');
        $endpoint = '/api/intake/status?reference=';
        $this->getJson($endpoint.str_repeat('B', 24))->assertNotFound();
        $this->getJson($endpoint.$reference)->assertOk()->assertJsonPath('submitted', false);
        $schema = json_decode(file_get_contents(resource_path('catalog/intake.json')), true, flags: JSON_THROW_ON_ERROR);
        $answers = ['has_important_date' => 'No'];
        foreach (array_merge(...array_column($schema, 'fields')) as $field) {
            $name = $field['name'];
            if (!$field['required'] || isset($field['when']) || isset($field['concerns']) || $name === 'consent_7' || isset($answers[$name])) {
                continue;
            }
            $option = $field['options'][0][0] ?? null;
            $answers[$name] = match ($field['type']) {
                'checkbox' => $option ? ['Not sure where to start' === $option ? $option : end($field['options'])[0]] : true,
                'email' => 'intake@example.test',
                'date' => '2026-11-28',
                'number' => '2',
                default => $option ?? 'Synthetic planning answer',
            };
        }
        if (array_intersect($answers['concerns'], ['Reentry planning', 'Family stability'])) {
            $answers['reentry_affected'] = 'No';
            $answers['family_affected'] = 'No';
        }
        $body = ['reference' => $reference, 'answers' => json_encode($answers, JSON_THROW_ON_ERROR)];
        $this->post('/api/intake', $body + ['files' => [UploadedFile::fake()->create('fixture.pdf', 8, 'application/pdf')]],
            ['Accept' => 'application/json'])->assertCreated();
        $this->postJson('/api/intake', $body)->assertConflict();
        $this->getJson($endpoint.$reference)->assertOk()->assertJsonPath('submitted', true);
        $id = DB::table('phelon_intakes')->value('id');
        $this->assertStringNotContainsString('intake@example.test', DB::table('phelon_intakes')->value('answers'));
        $this->getJson('/api/staff/intakes/'.$id)->assertUnauthorized();
        $this->getJson('/api/staff/intakes/'.$id.'/files/0')->assertUnauthorized();
        $this->actingAs($this->staff())->withSession(['staff_signed_at' => time()])
            ->getJson('/api/staff/intakes/'.$id)->assertOk()->assertJsonPath('answers.email', 'intake@example.test')
            ->assertJsonPath('labels.email', 'Email Address')->assertJsonPath('attachments', 1);
        $this->getJson('/api/staff/intakes')->assertOk()->assertJsonPath('intakes.0.appointment_ref', $reference)
            ->assertJsonMissingPath('intakes.0.answers')->assertJsonMissingPath('intakes.0.files');
        $this->getJson('/api/staff/intakes/'.($id + 1))->assertNotFound();
        $this->getJson('/api/staff/intakes/'.($id + 1).'/files/0')->assertNotFound();
        $this->get('/api/staff/intakes/'.$id.'/files/0')->assertDownload('attachment-1.pdf');
    }

    public function test_form_requests_preserve_validation_error_responses(): void
    {
        $this->withoutMiddleware(ThrottleRequests::class);
        $this->getJson('/api/availability?date=not-a-date')->assertUnprocessable()->assertJsonValidationErrors('date');
        $this->postJson('/api/session', [])->assertUnprocessable()->assertJsonValidationErrors(['email', 'password']);
        $this->getJson('/api/intake/status')->assertUnprocessable()->assertJsonValidationErrors('reference');
        $this->postJson('/api/intake', ['reference' => 'fixture'])->assertUnprocessable()->assertJsonValidationErrors('answers');
        $this->postJson('/api/library/verify', [])->assertUnprocessable()->assertJsonValidationErrors(['transaction', 'code']);
        $this->postJson('/api/library/resend', [])->assertUnprocessable()->assertJsonValidationErrors('transaction');
    }

    public function test_purchased_pdf_requires_a_current_matching_code(): void
    {
        config(['phelon.library_enabled' => true]);
        Schema::create('customers', function (Blueprint $table) {
            $table->string('transactionId')->unique();
            $table->string('total');
            $table->string('access_code');
            $table->dateTime('expiry_code');
            $table->string('email');
        });
        DB::table('customers')->insert(['transactionId' => 'synthetic-purchase-only',
            'total' => '47.00', 'access_code' => '123456', 'expiry_code' => now()->addMinutes(10), 'email' => 'buyer@example.test']);
        $this->get('/api/library/document')->assertForbidden();
        $this->postJson('/api/library/verify', ['transaction' => 'synthetic-purchase-only', 'code' => 'invalid'])->assertForbidden();
        $this->postJson('/api/library/verify', ['transaction' => 'synthetic-purchase-only', 'code' => '123456'])
            ->assertOk()->assertJsonPath('document', '/api/library/document');
        $this->get('/api/library/document')->assertDownload('47_starter_kit.pdf');
        DB::table('customers')->update(['access_code' => '987654']);
        $this->get('/api/library/document')->assertForbidden();
    }

    public function test_old_routes_redirect_without_reactivating_retired_endpoints(): void
    {
        $this->get('/Book.php?service=financial-stability')->assertRedirect('/book?service=financial-stability');
        $this->get('/Service.php?service=financial-stability')->assertRedirect('/services/financial-stability?service=financial-stability');
        $this->get('/webinar/Payment-497.php')->assertRedirect('/webinar/checkout/497');
        $this->get('/webhook.php')->assertGone();
        $this->getJson('/api/not-real')->assertNotFound();
    }
}
