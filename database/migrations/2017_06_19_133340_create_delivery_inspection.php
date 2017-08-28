<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliveryInspection extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_inspection', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('dr_id');
            $table->integer('upr_id');
            $table->integer('rfq_id')->nullable();
            $table->string('rfq_number')->nullable();
            $table->string('upr_number')->nullable();
            $table->string('status')->nullable();
            $table->string('delivery_number')->nullable();
            $table->string('inspection_number')->nullable();
            $table->text('update_remarks')->nullable();
            $table->date('start_date')->nullable();
            $table->date('closed_date')->nullable();
            $table->integer('started_by')->nullable();
            $table->integer('closed_by')->nullable();

            $table->integer('days')->nullable();
            $table->integer('close_days')->nullable();
            $table->text('remarks')->nullable();
            $table->text('close_remarks')->nullable();
            $table->text('action')->nullable();
            $table->text('close_action')->nullable();


            $table->integer('received_by')->nullable();
            $table->integer('inspected_by')->nullable();
            $table->integer('approved_by')->nullable();
            $table->integer('issued_by')->nullable();
            $table->integer('requested_by')->nullable();

            $table->text('received_signatory')->nullable();
            $table->text('inspected_signatory')->nullable();
            $table->text('approved_signatory')->nullable();
            $table->text('issued_signatory')->nullable();
            $table->text('requested_signatory')->nullable();

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
        Schema::dropIfExists('delivery_inspection');
    }
}
