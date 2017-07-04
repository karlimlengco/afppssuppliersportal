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
            $table->increments('id');
            $table->integer('rfq_id');
            $table->integer('upr_id');
            $table->integer('dr_id');
            $table->string('delivery_number')->nullable();
            $table->string('rfq_number')->nullable();
            $table->string('upr_number')->nullable();
            $table->date('inspection_date');
            $table->date('accepted_date')->nullable();
            $table->integer('accepted_by')->nullable();
            $table->string('nature_of_delivery')->nullable();
            $table->string('status')->nullable();
            $table->text('findings')->nullable();
            $table->text('recommendation')->nullable();
            $table->text('update_remarks')->nullable();
            $table->integer('prepared_by')->nullable();
            $table->integer('inspection_signatory')->nullable();
            $table->integer('acceptance_signatory')->nullable();
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
