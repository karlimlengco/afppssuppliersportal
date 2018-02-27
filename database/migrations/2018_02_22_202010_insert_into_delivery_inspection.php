<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertIntoDeliveryInspection extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('delivery_inspection', function($table)
        {
            $table->string('chairman_signatory')->nullable();
            $table->string('chairman_signatory_name')->nullable();
            $table->string('signatory_one')->nullable();
            $table->string('signatory_one_name')->nullable();
            $table->string('signatory_two')->nullable();
            $table->string('signatory_two_name')->nullable();
            $table->string('signatory_three')->nullable();
            $table->string('signatory_three_name')->nullable();
            $table->string('signatory_four')->nullable();
            $table->string('signatory_four_name')->nullable();
            $table->string('signatory_five')->nullable();
            $table->string('signatory_five_name')->nullable();
        });
    }

}
