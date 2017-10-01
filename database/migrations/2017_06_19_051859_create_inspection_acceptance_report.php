<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInspectionAcceptanceReport extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inspection_acceptance_report', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('rfq_id')->nullable();
            $table->string('upr_id');
            $table->string('dr_id');
            $table->string('delivery_number')->nullable();
            $table->string('rfq_number')->nullable();
            $table->string('upr_number')->nullable();
            $table->date('inspection_date');
            $table->date('accepted_date')->nullable();
            $table->string('accepted_by')->nullable();
            $table->string('nature_of_delivery')->nullable();
            $table->string('status')->nullable();
            $table->text('findings')->nullable();
            $table->text('recommendation')->nullable();
            $table->text('update_remarks')->nullable();
            $table->string('prepared_by')->nullable();
            $table->string('inspection_signatory')->nullable();
            $table->string('acceptance_signatory')->nullable();
            $table->integer('days')->nullable();
            $table->integer('accept_days')->nullable();
            $table->string('sao_signatory')->nullable();
            $table->text('remarks')->nullable();
            $table->text('accept_remarks')->nullable();
            $table->text('action')->nullable();
            $table->text('accept_action')->nullable();

            $table->string('inspection_name_signatory')->nullable();
            $table->string('acceptance_name_signatory')->nullable();
            $table->string('sao_name_signatory')->nullable();
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
        Schema::dropIfExists('inspection_acceptance_report');
    }
}
