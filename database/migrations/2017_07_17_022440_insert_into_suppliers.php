<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertIntoSuppliers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('suppliers', function($table)
        {
            $table->string('is_blocked')->default('0');
            $table->date('date_blocked')->nullable();
            $table->text('blocked_remarks')->nullable();
        });
    }

}
