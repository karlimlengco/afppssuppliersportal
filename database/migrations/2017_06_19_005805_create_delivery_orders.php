<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliveryOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('rfq_id');
            $table->integer('upr_id');
            $table->string('rfq_number')->nullable();
            $table->string('upr_number')->nullable();
            $table->integer('po_id');
            $table->date('expected_date');
            $table->date('delivery_date')->nullable();
            $table->string('delivery_number')->nullable();
            $table->string('status')->nullable();
            $table->string('inspection_status')->nullable();
            $table->date('transaction_date')->nullable();
            $table->integer('prepared_by')->nullable();
            $table->integer('signatory_id')->nullable();
            $table->integer('created_by')->nullable();
            $table->text('notes')->nullable();
            $table->date('date_completed')->nullable();
            $table->date('date_delivered_to_coa')->nullable();
            $table->integer('delivered_to_coa_by')->nullable();
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
        Schema::dropIfExists('delivery_orders');
    }
}
