<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patient_profiles', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement();
            $table->string('bornPlace');
            $table->string('familyGroup');
            $table->integer('familyPhone');
            $table->string('civilState');
            $table->string('scholarity');
            $table->string('occupation');
            $table->unsignedBigInteger('profile_id');
            
            $table->foreign('profile_id')->references('id')->on('profiles')
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
        Schema::dropIfExists('patient_profiles');
    }
}
