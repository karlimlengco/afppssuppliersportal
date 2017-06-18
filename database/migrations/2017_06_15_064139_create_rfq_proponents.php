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
            $table->increments('id');
            $table->integer('rfq_id');
            $table->integer('proponents');
            $table->string('note')->nullable();
            $table->string('bid_amount')->nullable();
            $table->date('date_processed');
            $table->integer('prepared_by');
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
