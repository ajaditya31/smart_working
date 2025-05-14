<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Event;
use App\Models\Attendee;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_book_event()
    {
        $event = Event::factory()->create(['capacity' => 2]);
        $attendee = Attendee::factory()->create();

        $response = $this->postJson('/api/bookings', [
            'event_id' => $event->id,
            'attendee_id' => $attendee->id,
        ]);

        $response->assertCreated()
                 ->assertJsonFragment(['event_id' => $event->id]);
    }

    public function test_user_cannot_double_book_same_event()
    {
        $event = Event::factory()->create(['capacity' => 2]);
        $attendee = Attendee::factory()->create();

        Booking::create([
            'event_id' => $event->id,
            'attendee_id' => $attendee->id,
            'booking_time' => now(),
        ]);

        $response = $this->postJson('/api/bookings', [
            'event_id' => $event->id,
            'attendee_id' => $attendee->id,
        ]);

        $response->assertStatus(422)
                 ->assertJsonFragment(['message' => 'You have already booked this event.']);
    }

    public function test_booking_fails_when_event_full()
    {
        $event = Event::factory()->create(['capacity' => 1]);
        $attendee1 = Attendee::factory()->create();
        $attendee2 = Attendee::factory()->create();

        Booking::create([
            'event_id' => $event->id,
            'attendee_id' => $attendee1->id,
            'booking_time' => now(),
        ]);

        $response = $this->postJson('/api/bookings', [
            'event_id' => $event->id,
            'attendee_id' => $attendee2->id,
        ]);

        $response->assertStatus(400)
                 ->assertJsonFragment(['message' => 'Event is at full capacity. Please select another event.']);
    }

    public function test_booking_fails_with_invalid_ids()
    {
        $response = $this->postJson('/api/bookings', [
            'event_id' => 999,
            'attendee_id' => 999,
        ]);

        $response->assertStatus(422);
    }

    public function test_booking_requires_required_fields()
    {
        $response = $this->postJson('/api/bookings', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['event_id', 'attendee_id']);
    }

    public function test_concurrent_bookings_respect_capacity()
    {
        $event = Event::factory()->create(['capacity' => 1]);

        $attendee1 = Attendee::factory()->create();
        $attendee2 = Attendee::factory()->create();

        // First booking
        $response1 = $this->postJson('/api/bookings', [
            'event_id' => $event->id,
            'attendee_id' => $attendee1->id,
        ]);

        // Second booking
        $response2 = $this->postJson('/api/bookings', [
            'event_id' => $event->id,
            'attendee_id' => $attendee2->id,
        ]);

        $response1->assertCreated();
        $response2->assertStatus(400)
            ->assertJsonFragment(['message' => 'Event is at full capacity. Please select another event.']);
    }

    public function test_show_booking_details()
    {
        $booking = Booking::factory()->create();

        $response = $this->getJson("/api/bookings/{$booking->id}");

        $response->assertOk()
            ->assertJsonFragment(['id' => $booking->id]);
    }

    public function test_user_can_cancel_booking()
    {
        $booking = Booking::factory()->create();

        $response = $this->deleteJson("/api/bookings/{$booking->id}");

        $response->assertNoContent();
        $this->assertDatabaseMissing('bookings', ['id' => $booking->id]);
    }

    public function test_user_cannot_book_past_event()
    {
        $event = Event::factory()->create([
            'start_time' => now()->subDay(),
            'capacity' => 10,
        ]);

        $attendee = Attendee::factory()->create();

        $response = $this->postJson('/api/bookings', [
            'event_id' => $event->id,
            'attendee_id' => $attendee->id,
        ]);

        $response->assertStatus(400)
            ->assertJsonFragment(['message' => 'Cannot book a past event.']);
    }
}

