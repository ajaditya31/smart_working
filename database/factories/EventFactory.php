<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition()
    {
        $start = Carbon::now()->addDays(rand(1, 5)); // Future datetime
        $end = (clone $start)->addHours(rand(1, 4));
        
        return [
            'name' => $this->faker->word,
            'created_at' => $this->faker->dateTime,
            'start_time' => $start,
            'end_time' => $end,
            'country' => $this->faker->country,
            'capacity' => $this->faker->numberBetween(1, 500)
        ];
    }
}
