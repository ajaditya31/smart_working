<?php

namespace App\Http\Controllers\Api;

use App\Models\Booking;
use App\Models\Event;
use App\Models\Attendee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingRequest;

class BookingController extends Controller
{
    public function store(StoreBookingRequest $request)
    {
        // Retrieve event and attendee
        $event = Event::findOrFail($request->event_id);
        $attendee = Attendee::findOrFail($request->attendee_id);

        // Prevent booking if event has already started
        if ($event->start_time && $event->start_time->isPast()) {
            return response()->json([
                'message' => 'Cannot book a past event.',
            ], 400);
        }

        // Check if the event is full
        $currentBookings = Booking::where('event_id', $event->id)->count();
        if ($currentBookings >= $event->capacity) {
            return response()->json([
                'message' => 'Event is at full capacity. Please select another event.',
            ], 400);
        }

        // Prevent duplicate bookings
        $existingBooking = Booking::where('event_id', $event->id)
                                  ->where('attendee_id', $attendee->id)
                                  ->exists();
        if ($existingBooking) {
            return response()->json([
                'message' => 'You have already booked this event.',
            ], 422);
        }
        

        // Create the booking
        $booking = Booking::create([
            'event_id' => $event->id,
            'attendee_id' => $attendee->id,
            'booking_time' => now(),
        ]);

        // Return success response
        return response()->json([
            'message' => 'Booking successfully created.',
            'data' => $booking,
        ], 201);
    }

    public function show($id)
    {
        $booking = Booking::with(['event', 'attendee'])->findOrFail($id);
        return response()->json([
            'data' => $booking,
        ]);
    }

    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();
        return response()->json(null, 204); // No content on successful delete
    }
}
