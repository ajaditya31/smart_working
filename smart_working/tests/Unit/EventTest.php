<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Event;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventTest extends TestCase
{
    use RefreshDatabase;

    public function test_event_has_bookings()
    {
        $event = Event::factory()->create();
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $event->bookings);
    }
}

