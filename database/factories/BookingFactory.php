<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\Attendee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'event_id' => Event::factory(),         // Creates a new Event if none provided
            'attendee_id' => Attendee::factory(),   // Creates a new Attendee if none provided
            'booking_time' => now(),
        ];
    }
}
