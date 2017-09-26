<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNoticeToProceed extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notice_to_proceed', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('po_id');
            $table->string('rfq_id')->nullable();
            $table->string('upr_id');
            $table->string('rfq_number')->nullable();
            $table->string('upr_number')->nullable();
            $table->string('signatory_id')->nullable();
            $table->text('signatory')->nullable();
            $table->string('proponent_id');
            $table->string('prepared_by')->nullable();
            $table->timestamp('prepared_date')->nullable();
            $table->string('status')->nullable();
            $table->text('remarks')->nullable();
            $table->text('action')->nullable();
            $table->text('update_remarks')->nullable();
            $table->text('file')->nullable();

            $table->string('days')->nullable();
            $table->string('accepted_days')->nullable();
            $table->text('accepted_remarks')->nullable();
            $table->text('accepted_action')->nullable();

            $table->string('received_by')->nullable();
            $table->date('award_accepted_date')->nullable();
            $table->date('accepted_date')->nullable();
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
        Schema::dropIfExists('notice_to_proceed');
    }
}
