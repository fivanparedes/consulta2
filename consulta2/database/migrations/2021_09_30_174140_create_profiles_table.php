<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement();
            $table->date('bornDate');
            $table->string('gender');
            $table->integer('phone');
            $table->string('address');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('patient_profile_id')->nullable();
            $table->unsignedBigInteger('professional_profile_id')->nullable();
            $table->timestamps();
        });

        Schema::create('institutions', function(Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->string('name');
            $table->string('description');
            $table->string('address');
            $table->string('phone');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profiles');
    }
}
