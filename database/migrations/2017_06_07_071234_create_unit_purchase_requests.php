<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUnitPurchaseRequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('unit_purchase_requests', function (Blueprint $table) {
            $table->uuid('id')->unique();

            $table->string('place_of_delivery');
            $table->string('procurement_office');
            $table->string('mode_of_procurement');
            $table->string('chargeability');
            $table->string('procurement_type');
            $table->string('total_amount');

            $table->string('fund_validity')->nullable();
            $table->string('terms_of_payment')->nullable();
            $table->string('other_infos')->nullable();

            $table->string('units');
            $table->text('purpose');

            $table->string('project_name')->nullable();
            $table->string('upr_number')->nullable();
            $table->string('ref_number')->nullable();

            $table->date('date_prepared');
            $table->string('prepared_by');

            $table->date('completed_at')->nullable();

            $table->date('cancelled_at')->nullable();
            $table->text('cancel_reason')->nullable();

            $table->text('remarks')->nullable();
            $table->text('update_remarks')->nullable();

            $table->timestamp('date_processed')->nullable();
            $table->string('processed_by')->nullable();

            $table->timestamp('terminated_date')->nullable();
            $table->string('terminate_status')->nullable();
            $table->string('terminated_by')->nullable();

            $table->string('requestor_id')->nullable();
            $table->string('count_id')->nullable();
            $table->text('requestor_text')->nullable();
            $table->string('fund_signatory_id')->nullable();
            $table->text('fund_signatory_text')->nullable();
            $table->string('approver_id')->nullable();
            $table->text('approver_text')->nullable();


            $table->string('status')->default('upr_processing');
            $table->string('state')->default('upr_processing');

            $table->string('days')->nullable();
            $table->string('delay_count')->nullable();
            $table->string('calendar_days')->nullable();
            $table->text('last_remarks')->nullable();
            $table->text('last_action')->nullable();


            $table->timestamps();

            $table->index('upr_number');
            $table->index('ref_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('unit_purchase_requests');
    }
}
