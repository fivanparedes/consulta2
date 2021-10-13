<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLifesheetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lifesheets', function (Blueprint $table) {
            $table->id();
            $table->multiLineString('diseases');
            $table->multiLineString('surgeries');
            $table->multiLineString('medication');
            $table->multiLineString('allergies');
            $table->integer('smokes');
            $table->integer('drinks');
            $table->integer('exercises');
            $table->string('hceu');
            $table->unsignedBigInteger('patient_profile_id');
            $table->timestamps();
        });

        Schema::create('medical_histories', function (Blueprint $table) {
            $table->id();
            $table->date('indate');
            $table->multiLineString('psicological_history');
            $table->string('visitreason');
            $table->string('diagnosis');
            $table->string('clinical_history')->nullable();
            $table->unsignedBigInteger('patient_profile_id');
            $table->timestamps();
        });

        Schema::create('coverages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address');
            $table->string('phone');
            $table->boolean('supported');
            $table->unsignedBigInteger('lifesheet_id');
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
        Schema::dropIfExists('lifesheets');
    }
}
