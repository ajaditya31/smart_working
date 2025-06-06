<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
	public function up()
	{
		Schema::create('events', function (Blueprint $table) {
			$table->id();
			$table->string('name');
			$table->text('description')->nullable();
			$table->string('country'); // Country as location
			$table->dateTime('start_time');
			$table->dateTime('end_time');
			$table->unsignedInteger('capacity'); // Event capacity
			$table->timestamps();
		});
	}

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
}

