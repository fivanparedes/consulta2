<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfessionalProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('professional_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('licensePlate');
            $table->integer('status');
            $table->string('field');
            $table->unsignedBigInteger('specialty_id');
            $table->unsignedBigInteger('profile_id');
            $table->unsignedBigInteger('institution_id');
            $table->foreign('profile_id')->references('id')->on('profiles')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('institution_id')->references('id')->on('institution_profiles')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('specialty_id')->references('id')->on('specialties')
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
        Schema::dropIfExists('professional_profiles');
    }
}