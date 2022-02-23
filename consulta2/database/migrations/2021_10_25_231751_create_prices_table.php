<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prices', function (Blueprint $table) {
            $table->id();
            $table->float('price');
            $table->float('copayment');
            $table->integer('practice_id');
            $table->unsignedBigInteger('coverage_id');
            $table->foreign('practice_id')->references('id')->on('practices')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('coverage_id')->references('id')->on('coverages')
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
        Schema::dropIfExists('prices');
    }
}