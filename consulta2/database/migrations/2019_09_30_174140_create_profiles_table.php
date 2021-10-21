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
            $table->string('phone');
            $table->string('address');
            $table->unsignedBigInteger('user_id');
            $table->integer('city_id');
            
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('city_id')->references('id')->on('cities')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('institution_profiles', function(Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->integer('city_id');
            $table->string('name');
            $table->string('description');
            $table->string('address');
            $table->string('phone');
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('city_id')->references('id')->on('cities')
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
        Schema::dropIfExists('profiles');
    }
}