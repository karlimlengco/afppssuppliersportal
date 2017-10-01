<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBidDocsIssuance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bid_docs_issuance', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('upr_id');
            $table->string('upr_number')->nullable();
            $table->string('ref_number')->nullable();
            $table->date('transaction_date');
            $table->text('update_remarks')->nullable();
            $table->string('proponent_id');
            $table->string('proponent_name');
            $table->text('action')->nullable();
            $table->string('processed_by')->nullable();
            $table->text('remarks')->nullable();
            $table->integer('days')->nullable();
            $table->integer('is_lcb')->nullable();
            $table->integer('is_scb')->nullable();
            $table->string('status')->nullable();
            $table->string('bid_amount')->nullable();
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
        Schema::dropIfExists('bid_docs_issuance');
    }
}
