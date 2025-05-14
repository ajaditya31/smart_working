<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    public function up(): void
    {
		Schema::create('bookings', function (Blueprint $table) {
			$table->id();
			$table->foreignId('event_id')->constrained()->onDelete('cascade');
			$table->foreignId('attendee_id')->constrained()->onDelete('cascade');
			$table->dateTime('booking_time');
			$table->timestamps();

			// Prevent duplicate bookings for the same event and attendee
			$table->unique(['event_id', 'attendee_id']);
		});
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
}
