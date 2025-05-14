<?php

namespace App\Http\Controllers\Api;

use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;

class EventController extends Controller
{
    public function store(StoreEventRequest $request)
    {
        // Create the event
        $event = Event::create($request->validated());

        // Return response with created event
        return response()->json([
            'message' => 'Event created successfully.',
            'data' => $event,
        ], 201);
    }

    public function show($id)
    {
        $event = Event::withCount('bookings') // Get the number of bookings
            ->findOrFail($id);

        $event->remaining_capacity = $event->capacity - $event->bookings_count;

        return response()->json([
            'data' => $event,
            'remaining_capacity' => $event->remaining_capacity,
        ]);
    }

    public function index()
    {
        $events = Event::withCount('bookings') // Get number of bookings for each event
            ->get()
            ->map(function ($event) {
                $event->remaining_capacity = $event->capacity - $event->bookings_count;
                return $event;
            });

        return response()->json([
            'data' => $events,
        ]);
    }
}
