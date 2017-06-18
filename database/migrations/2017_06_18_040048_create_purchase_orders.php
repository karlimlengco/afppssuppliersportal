<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('rfq_id');
            $table->integer('upr_id');
            $table->string('rfq_number')->nullable();
            $table->string('upr_number')->nullable();
            $table->date('purchase_date');
            $table->string('bid_amount');
            $table->integer('payment_term');
            $table->integer('prepared_by');
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
        Schema::dropIfExists('purchase_orders');
    }
}
