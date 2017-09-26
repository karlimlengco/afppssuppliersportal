<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRfqProponents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rfq_proponents', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('rfq_id');
            $table->string('proponents');
            $table->string('note')->nullable();
            $table->string('bid_amount')->nullable();
            $table->text('remarks')->nullable();
            $table->date('date_processed');
            $table->string('prepared_by');
            $table->string('status')->nullable();
            $table->string('is_awarded')->nullable();
            $table->string('is_award_accepted')->nullable();
            $table->string('received_by')->nullable();
            $table->date('award_accepted_date')->nullable();
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
        Schema::dropIfExists('rfq_proponents');
    }
}
