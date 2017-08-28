<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestForQuotations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_for_quotations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('upr_id');

            $table->string('upr_number')->nullable();
            $table->string('rfq_number')->nullable();

            $table->date('deadline')->nullable();
            $table->time('opening_time')->nullable();
            $table->date('transaction_date');
            $table->timestamp('completed_at')->nullable();

            $table->string('status')->default('pending');
            $table->text('remarks')->nullable();
            $table->string('action')->nullable();
            $table->text('update_remarks')->nullable();
            $table->integer('processed_by')->nullable();
            $table->integer('days')->nullable();
            $table->integer('close_days')->nullable();
            $table->text('close_remarks')->nullable();
            $table->text('close_action')->nullable();

            $table->integer('awarded_to')->nullable();
            $table->integer('chief')->nullable();
            $table->text('signatory_chief')->nullable();
            $table->date('awarded_date')->nullable();
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
        Schema::dropIfExists('request_for_quotations');
    }
}
