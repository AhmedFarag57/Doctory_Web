<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorTimeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctor_time', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doc_id')->constrained('doctors')->cascadeOnDelete();
            $table->string('startime');
            $table->string('endtime');
            $table->date('date');
            $table->boolean('reserved')->default(0);
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
        Schema::dropIfExists('doctor_time');
    }
}
