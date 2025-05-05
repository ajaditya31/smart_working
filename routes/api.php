<?php

use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\AttendeeController;
use App\Http\Controllers\Api\BookingController;

// Events
Route::apiResource('events', EventController::class);

// Attendees (only index and store)
Route::get('/attendees', [AttendeeController::class, 'index']);
Route::post('/attendees', [AttendeeController::class, 'store']);

// Bookings
Route::post('/bookings', [BookingController::class, 'store']);
Route::get('/bookings/{id}', [BookingController::class, 'show']);
Route::delete('/bookings/{id}', [BookingController::class, 'destroy']);
