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
            $table->increments('id');
            $table->integer('po_id');
            $table->integer('rfq_id');
            $table->integer('upr_id');
            $table->string('rfq_number')->nullable();
            $table->string('upr_number')->nullable();
            $table->integer('signatory_id')->nullable();
            $table->integer('proponent_id');
            $table->integer('prepared_by')->nullable();
            $table->timestamp('prepared_date')->nullable();
            $table->string('status')->nullable();
            $table->text('remarks')->nullable();
            $table->text('file')->nullable();

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
