<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMeetingMinutes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meeting_minutes', function (Blueprint $table) {
            $table->uuid('id');
            $table->date('date_opened');
            $table->time('time_opened');
            $table->string('venue');
            $table->string('officer_id');
            $table->string('prepared_by');
            $table->time('time_closed')->nullable();
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
        Schema::dropIfExists('meeting_minutes');
    }
}
