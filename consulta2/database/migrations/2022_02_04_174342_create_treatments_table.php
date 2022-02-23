<?php

use App\Models\MedicalHistory;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTreatmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('treatments', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->default("** SIN DATOS **");
            $table->string('description', 100)->default("** SIN DATOS **");
            $table->integer('times_per_month')->default(0);
            $table->time('preferred_time')->nullable();
            $table->string('preferred_days', 20);
            $table->date('start')->default(now());
            $table->date('end')->default(now());
            $table->unsignedBigInteger('medical_history_id');
            $table->foreignIdFor(MedicalHistory::class);
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
        Schema::dropIfExists('treatments');
    }
}
