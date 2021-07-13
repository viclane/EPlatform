<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    { // du prof ? Yep
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date_start');
            $table->dateTime('date_fin');
            $table->timestamps();
            $table->foreignId('course_id');
            $table->foreign('course_id')->references('id')->on('cours');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schedules');
    }
}
