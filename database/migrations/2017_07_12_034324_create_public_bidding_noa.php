<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePublicBiddingNoa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('public_bidding_noa', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('rfb_id');
            $table->integer('upr_id');
            $table->integer('supplier_id');
            $table->string('upr_number')->nullable();
            $table->string('rfb_number')->nullable();

            $table->date('received_noa')->nullable();
            $table->string('received_by')->nullable();

            $table->date('approved_noa')->nullable();

            $table->date('supplier_received_noa')->nullable();
            $table->string('supplier_received_by')->nullable();

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
        Schema::dropIfExists('public_bidding_noa');
    }
}
