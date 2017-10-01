<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertIntoNoa extends Migration
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
            $table->date('perfomance_bond')->nullable();
            $table->string('amount')->nullable();
            $table->text('notes')->nullable();
        });
    }

}
