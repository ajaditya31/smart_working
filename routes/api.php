<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\AttendeeController;
use App\Http\Controllers\Api\BookingController;

//test API

Route::get('/test-api', function () {
    return response()->json(['message' => 'API is working']);
});

// Events
Route::apiResource('events', EventController::class);

// Attendees (only index and store)
Route::get('/attendees', [AttendeeController::class, 'index']);
Route::post('/attendees', [AttendeeController::class, 'store']);

// Bookings
Route::post('/bookings', [BookingController::class, 'store']);
Route::get('/bookings/{id}', [BookingController::class, 'show']);
Route::delete('/bookings/{id}', [BookingController::class, 'destroy']);
