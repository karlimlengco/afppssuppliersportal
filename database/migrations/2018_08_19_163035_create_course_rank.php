<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourseRank extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_rank', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('course_offer_id');
            $table->integer('terms');
            $table->integer('academic_year');
            $table->integer('class_level');
            $table->integer('course_rank');
            $table->integer('cadet_id');
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
        Schema::dropIfExists('course_rank');
    }
}