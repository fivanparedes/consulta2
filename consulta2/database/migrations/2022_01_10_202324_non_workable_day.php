<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NonWorkableDay extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('non_workable_days', function(Blueprint $table) {
            $table->id();
            $table->string('concept');
            $table->date('from');
            $table->date('to');
            $table->unsignedBigInteger('professional_profile_id');
            $table->timestamps();
            $table->foreign('professional_profile_id')->references('id')
                ->on('professional_profiles')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('non_workable_days');
    }
}
