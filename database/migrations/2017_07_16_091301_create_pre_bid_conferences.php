<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePreBidConferences extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pre_bid_conferences', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('upr_id');
            $table->string('upr_number')->nullable();
            $table->string('ref_number')->nullable();
            $table->date('transaction_date');
            $table->text('update_remarks')->nullable();
            $table->integer('is_scb_issue')->nullable();
            $table->date('sbb_date')->nullable();
            $table->integer('is_resched')->nullable();
            $table->text('action')->nullable();
            $table->date('bid_opening_date')->nullable();
            $table->date('resched_date')->nullable();
            $table->text('resched_remarks')->nullable();
            $table->text('remarks')->nullable();
            $table->integer('days')->nullable();
            $table->integer('processed_by')->nullable();

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
        Schema::dropIfExists('pre_bid_conferences');
    }
}
