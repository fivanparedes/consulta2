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
            $table->unsignedBigInteger('professional_profile_id');
            $table->string('title');
            $table->dateTime('start')->unique();
            $table->dateTime('end')->unique();
            $table->boolean('confirmed');

            $table->foreign('professional_profile_id')
                ->references('id')->on('professional_profiles')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('calendar_event_patient', function (Blueprint $table) {
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('calendar_event_id');
            $table->foreign('patient_id')->references('id')->on('patient_profiles')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('calendar_event_id')->references('id')->on('calendar_events')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->primary(['patient_id', 'calendar_event_id']);
        });

        Schema::create('cites', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement();
            $table->unsignedBigInteger('calendar_event_id');
            $table->integer('practice_id');
            $table->boolean('assisted');
            $table->boolean('isVirtual');
            $table->boolean('covered');
            $table->boolean('paid');
            $table->foreign('calendar_event_id')
                ->references('id')->on('calendar_events')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('practice_id')
                ->references('id')->on('practices')
                ->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('cites');
        Schema::dropIfExists('calendar_event_patient');
    }
}