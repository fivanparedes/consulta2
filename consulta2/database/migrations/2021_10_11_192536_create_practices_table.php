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
            $table->timestamps();
        });
        Schema::create('practices', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->string('name');
            $table->unsignedBigInteger('nomenclature_id');
            $table->integer('consult_type_id');
            $table->foreign('nomenclature_id')->references('id')->on('nomenclatures')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->string('description');
            $table->integer('maxtime');
            $table->foreign('consult_type_id')->references('id')->on('consult_types')
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
        Schema::dropIfExists('practices');
        Schema::dropIfExists('consult_types');
    }
}