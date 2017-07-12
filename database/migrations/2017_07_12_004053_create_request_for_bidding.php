<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestForBidding extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_for_bidding', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('upr_id');

            $table->string('upr_number')->nullable();
            $table->string('rfb_number')->nullable();

            $table->string('bac_id')->nullable();

            $table->date('transaction_date');
            $table->date('released_date');

            $table->date('received_date')->nullable();
            $table->string('received_by')->nullable();

            $table->string('status')->nullable();
            $table->string('remarks')->nullable();
            $table->string('update_remarks')->nullable();
            $table->integer('processed_by')->nullable();


            $table->integer('days')->nullable();

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
        Schema::dropIfExists('request_for_bidding');
    }
}
