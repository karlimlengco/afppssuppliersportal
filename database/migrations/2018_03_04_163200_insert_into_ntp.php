<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertIntoNtp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('notice_to_proceed', function($table)
        {
            $table->date('philgeps_posting')->nullable();
            $table->string('philgeps_remarks')->nullable();
            $table->string('philgeps_action')->nullable();
            $table->string('philgeps_days')->nullable();
        });
    }
}
