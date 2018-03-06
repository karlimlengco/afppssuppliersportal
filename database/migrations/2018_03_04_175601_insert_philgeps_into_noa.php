<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertPhilgepsIntoNoa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notice_of_awards', function($table)
        {
            $table->date('philgeps_posting')->nullable();
            $table->string('philgeps_remarks')->nullable();
            $table->string('philgeps_action')->nullable();
            $table->string('philgeps_days')->nullable();
        });

    }

}
