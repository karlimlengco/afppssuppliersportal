<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBidOpening extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bid_opening', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('upr_id');
            $table->string('upr_number')->nullable();
            $table->string('ref_number')->nullable();
            $table->date('transaction_date');
            $table->text('update_remarks')->nullable();
            $table->date('closing_date')->nullable();
            $table->text('action')->nullable();
            $table->date('failed_date')->nullable();
            $table->text('failed_remarks')->nullable();
            $table->integer('is_completed')->nullable();
            $table->integer('days')->nullable();
            $table->text('remarks')->nullable();
            $table->integer('processed_by')->nullable();
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
        Schema::dropIfExists('bid_opening');
    }
}
