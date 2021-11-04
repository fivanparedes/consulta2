<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessHoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_hours', function (Blueprint $table) {
            $table->id();
            $table->time('time');
            $table->timestamps();
        });

        Schema::create('hours_professionals', function (Blueprint $table) {
            $table->unsignedBigInteger('professional_profile_id');
            $table->unsignedBigInteger('business_hour_id');
            $table->foreign('professional_profile_id')->references('id')->on('professional_profiles')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('business_hour_id')->references('id')->on('business_hours')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->primary(['business_hour_id', 'professional_profile_id'], 'professional_business_hours');
        });

        Schema::create('business_hour_consult_type', function (Blueprint $table) {
            $table->unsignedBigInteger('business_hour_id');
            $table->integer('consult_type_id');
            $table->foreign('business_hour_id')->references('id')->on('business_hours')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('consult_type_id')->references('id')->on('consult_types')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->primary(['business_hour_id', 'consult_type_id'], 'business_hours_consult_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('business_hours');
        Schema::dropIfExists('hours_professionals');
        Schema::dropIfExists('business_hour_consult_type');
    }
}