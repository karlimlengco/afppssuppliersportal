<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertIntoMessages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('messages', function($table)
        {
            $table->integer('chat_id')->unsigned();
            $table->string('status')->nullable();
            $table->string('seen_by')->nullable();
            $table->integer('is_seen')->nullable();
        });
    }


}
