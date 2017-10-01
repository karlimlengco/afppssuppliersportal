<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBidDocsFinalized extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bid_docs_finalized', function (Blueprint $table) {
            $table->uuid('id');
            $table->integer('upr_id');
            $table->string('upr_number')->nullable();
            $table->string('ref_number')->nullable();
            $table->date('transaction_date');
            $table->text('update_remarks')->nullable();
            $table->text('remarks')->nullable();
            $table->text('action')->nullable();
            $table->integer('days')->nullable();
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
        Schema::dropIfExists('bid_docs_finalized');
    }
}
