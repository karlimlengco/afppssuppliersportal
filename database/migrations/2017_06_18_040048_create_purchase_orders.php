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
            $table->uuid('id');
            $table->string('rfq_id')->nullable();
            $table->string('upr_id');
            $table->string('rfq_number')->nullable();
            $table->string('upr_number')->nullable();
            $table->string('po_number')->nullable();
            $table->date('purchase_date');
            $table->string('bid_amount');
            $table->string('payment_term');
            $table->integer('delivery_terms');
            $table->integer('delivery_date')->nullable();
            $table->string('prepared_by');
            $table->string('type')->nullable();
            $table->string('status')->nullable();

            $table->text('remarks')->nullable();
            $table->integer('days')->nullable();
            $table->integer('funding_days')->nullable();
            $table->integer('mfo_days')->nullable();
            $table->integer('coa_days')->nullable();

            $table->text('action')->nullable();
            $table->text('funding_action')->nullable();
            $table->text('coa_action')->nullable();
            $table->text('mfo_action')->nullable();

            $table->date('funding_released_date')->nullable();
            $table->date('funding_received_date')->nullable();
            $table->text('funding_remarks')->nullable();

            $table->date('mfo_received_date')->nullable();
            $table->date('mfo_released_date')->nullable();
            $table->text('mfo_remarks')->nullable();
            $table->text('coa_remarks')->nullable();

            $table->string('received_by')->nullable();
            $table->string('signatory_id')->nullable();
            $table->string('requestor_id')->nullable();
            $table->string('accounting_id')->nullable();
            $table->string('update_remarks')->nullable();

            $table->string('coa_signatory')->nullable();
            $table->string('approver_id')->nullable();
            $table->date('coa_approved_date')->nullable();
            $table->string('coa_approved')->nullable();

            $table->string('requestor_signatory')->nullable();
            $table->string('accounting_signatory')->nullable();
            $table->string('approver_signatory')->nullable();
            $table->string('coa_name_signatory')->nullable();

            $table->text('coa_file')->nullable();
            $table->date('award_accepted_date')->nullable();
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
