<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePracticesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consult_types', function(Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->string('name');
            $table->string('availability');
            $table->boolean('visible');
            $table->boolean('requires_auth');
            $table->integer('allowed_modes');
            $table->unsignedBigInteger('professional_profile_id');
            $table->foreign('professional_profile_id')->references('id')->on('professional_profiles')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
        Schema::create('practices', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->string('name');
            $table->integer('maxtime');
            $table->unsignedBigInteger('nomenclature_id');
            $table->integer('consult_type_id');
            $table->unsignedBigInteger('coverage_id');
            $table->foreign('nomenclature_id')->references('id')->on('nomenclatures')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('coverage_id')->references('id')->on('coverages')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->string('description');
            $table->timestamps();
        });

        Schema::create('consult_type_practice', function (Blueprint $table){
            $table->integer('practice_id');
            $table->integer('consult_type_id');
            $table->foreign('practice_id')->references('id')->on('practices')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('consult_type_id')->references('id')->on('consult_types')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->primary(['practice_id', 'consult_type_id'], 'practice_consult_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('practices');
        Schema::dropIfExists('consult_types');
        Schema::dropIfExists('consult_type_practice');
    }
}