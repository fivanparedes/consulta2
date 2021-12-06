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
        Schema::create('coverages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address');
            $table->string('phone');
            $table->integer('city_id');
            $table->foreign('city_id')->references('id')->on('cities')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('coverage_professionals', function (Blueprint $table) {
            $table->unsignedBigInteger('professional_id');
            $table->unsignedBigInteger('coverage_id');
            $table->foreign('professional_id')->references('id')->on('professional_profiles')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('coverage_id')->references('id')->on('coverages')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->primary(['professional_id', 'coverage_id'], 'coverage_professionals');
        });
        Schema::create('lifesheets', function (Blueprint $table) {
            $table->id();
            $table->string('diseases');
            $table->string('surgeries');
            $table->string('medication');
            $table->string('allergies');
            $table->integer('smokes');
            $table->integer('drinks');
            $table->integer('exercises');
            $table->string('hceu');
            $table->unsignedBigInteger('patient_profile_id');
            $table->unsignedBigInteger('coverage_id');
            $table->foreign('patient_profile_id')->references('id')->on('patient_profiles')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('coverage_id')->references('id')->on('coverages')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('medical_histories', function (Blueprint $table) {
            $table->id();
            $table->string('indate');
            $table->string('psicological_history');
            $table->string('visitreason');
            $table->string('diagnosis');
            $table->string('clinical_history')->nullable();
            $table->unsignedBigInteger('patient_profile_id');
            $table->foreign('patient_profile_id')->references('id')->on('patient_profiles')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('professional_profile_id');
            $table->foreign('professional_profile_id')->references('id')->on('professional_profiles')
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
        Schema::dropIfExists('lifesheets');
        Schema::dropIfExists('coverages');
        Schema::dropIfExists('coverage_professionals');
        Schema::dropIfExists('medical_histories');
    }
}
