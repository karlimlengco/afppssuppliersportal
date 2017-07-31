<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertIntoCanvassing extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('canvassing', function($table)
        {
            $table->integer('is_failed')->nullable();
            $table->text('failed_remarks')->nullable();
            $table->date('date_failed')->nullable();
        });
    }
}
