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
}

