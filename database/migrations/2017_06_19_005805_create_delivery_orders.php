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
            $table->uuid('id');
            $table->string('rfq_id')->nullable();
            $table->string('upr_id');
            $table->string('rfq_number')->nullable();
            $table->string('upr_number')->nullable();
            $table->string('po_id');
            $table->date('expected_date');
            $table->string('delivery_number')->nullable();
            $table->string('status')->nullable();
            $table->string('inspection_status')->nullable();
            $table->string('received_by')->nullable();
            $table->string('prepared_by')->nullable();
            $table->string('signatory_id')->nullable();
            $table->text('signatory')->nullable();
            $table->string('created_by')->nullable();
            $table->text('notes')->nullable();
            $table->text('update_remarks')->nullable();

            $table->date('transaction_date')->nullable();
            $table->date('delivery_date')->nullable();
            $table->string('delivery_status')->nullable();
            $table->date('date_completed')->nullable();
            $table->date('date_delivered_to_coa')->nullable();

            $table->integer('days')->nullable();
            $table->text('remarks')->nullable();
            $table->integer('delivery_days')->nullable();
            $table->text('delivery_remarks')->nullable();
            $table->integer('dr_coa_days')->nullable();
            $table->text('dr_coa_remarks')->nullable();
            $table->text('action')->nullable();
            $table->text('delivery_action')->nullable();
            $table->text('dr_coa_action')->nullable();

            $table->string('delivered_to_coa_by')->nullable();
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
