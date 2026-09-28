<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Service;
use App\Services\AvailabilityService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Maandag 5 oktober 2026, 08:00 — voor openingstijd.
        Carbon::setTestNow('2026-10-05 08:00:00');
    }

    public function test_homepage_lists_services(): void
    {
        Service::factory()->create(['name' => 'Knippen']);

        $this->get('/')->assertOk()->assertSee('Knippen');
    }

    public function test_booked_times_are_not_offered(): void
    {
        $service = Service::factory()->create(['duration_minutes' => 30]);
        Appointment::factory()->for($service)->create([
            'starts_at' => '2026-10-05 10:00',
            'ends_at' => '2026-10-05 11:00',
        ]);

        $slots = app(AvailabilityService::class)
            ->slotsFor($service, Carbon::parse('2026-10-05'))
            ->map->format('H:i');

        $this->assertContains('09:30', $slots);   // eindigt precies om 10:00
        $this->assertNotContains('09:45', $slots); // zou overlappen
        $this->assertNotContains('10:30', $slots);
        $this->assertContains('11:00', $slots);
        $this->assertSame('17:00', $slots->last()); // laatste half uur voor sluitingstijd
    }

    public function test_salon_is_closed_on_sunday(): void
    {
        $service = Service::factory()->create();

        $this->assertTrue(app(AvailabilityService::class)->slotsFor($service, Carbon::parse('2026-10-11'))->isEmpty());
    }

    public function test_past_times_are_not_offered(): void
    {
        Carbon::setTestNow('2026-10-05 14:10:00');
        $service = Service::factory()->create();

        $first = app(AvailabilityService::class)->slotsFor($service, Carbon::parse('2026-10-05'))->first();

        $this->assertSame('14:15', $first->format('H:i'));
    }

    public function test_customer_can_book_a_free_slot(): void
    {
        $service = Service::factory()->create(['duration_minutes' => 60]);

        $response = $this->post(route('booking.store', $service), [
            'starts_at' => '2026-10-06 13:00',
            'customer_name' => 'Sanne de Vries',
            'customer_email' => 'sanne@example.com',
        ]);

        $appointment = Appointment::sole();
        $response->assertRedirect(URL::signedRoute('booking.confirmation', $appointment));
        $this->assertSame('2026-10-06 14:00', $appointment->ends_at->format('Y-m-d H:i'));
    }

    public function test_a_taken_slot_cannot_be_booked_twice(): void
    {
        $service = Service::factory()->create(['duration_minutes' => 30]);
        Appointment::factory()->for($service)->create([
            'starts_at' => '2026-10-06 13:00',
            'ends_at' => '2026-10-06 13:30',
        ]);

        $this->from(route('booking.show', $service))
            ->post(route('booking.store', $service), [
                'starts_at' => '2026-10-06 13:00',
                'customer_name' => 'Tom Jansen',
                'customer_email' => 'tom@example.com',
            ])
            ->assertRedirect(route('booking.show', $service))
            ->assertSessionHasErrors('starts_at');

        $this->assertSame(1, Appointment::count());
    }

    public function test_booking_requires_name_and_valid_email(): void
    {
        $service = Service::factory()->create();

        $this->post(route('booking.store', $service), ['starts_at' => '2026-10-06 13:00', 'customer_email' => 'geen-email'])
            ->assertSessionHasErrors(['customer_name', 'customer_email']);
    }

    public function test_confirmation_page_needs_a_valid_signature(): void
    {
        $appointment = Appointment::factory()->create();

        $this->get(route('booking.confirmation', $appointment))->assertForbidden();
        $this->get(URL::signedRoute('booking.confirmation', $appointment))->assertOk()->assertSee($appointment->customer_name);
    }

    public function test_admin_can_see_and_cancel_appointments(): void
    {
        $appointment = Appointment::factory()->create([
            'customer_name' => 'Fatima El Amrani',
            'starts_at' => '2026-10-05 10:00',
            'ends_at' => '2026-10-05 10:30',
        ]);

        $this->get(route('admin.index'))->assertOk()->assertSee('Fatima El Amrani');

        $this->delete(route('admin.destroy', $appointment))->assertRedirect();
        $this->assertModelMissing($appointment);
    }
}
