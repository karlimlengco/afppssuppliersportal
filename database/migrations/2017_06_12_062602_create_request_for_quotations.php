<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestForQuotations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_for_quotations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('upr_id');
            $table->string('upr_number')->nullable();
            $table->string('rfq_number')->nullable();
            $table->date('deadline');
            $table->string('status')->default('pending');
            $table->time('opening_time');
            $table->date('transaction_date');
            $table->integer('awarded_to')->nullable();
            $table->date('awarded_date')->nullable();
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
        Schema::dropIfExists('request_for_quotations');
    }
}
