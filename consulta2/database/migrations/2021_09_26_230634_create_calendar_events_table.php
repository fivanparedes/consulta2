<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCalendarEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calendar_events', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement();
            $table->unsignedBigInteger('user_id');
            $table->string('title');
            $table->dateTime('start')->unique();
            $table->dateTime('end')->unique();
            $table->boolean('confirmed');
            $table->unsignedBigInteger('cite_id');
            $table->timestamps();
        });

        Schema::create('cites', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement();
            $table->unsignedBigInteger('calendar_event_id');
            $table->boolean('assisted');
            $table->boolean('isVirtual');
            $table->boolean('covered');
            $table->boolean('paid');
            $table->integer('practice_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('calendar_events');
    }
}
