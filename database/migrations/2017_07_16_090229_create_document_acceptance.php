<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentAcceptance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_acceptance', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('upr_id');
            $table->integer('bac_id');
            $table->string('upr_number')->nullable();
            $table->string('ref_number')->nullable();
            $table->date('transaction_date');
            $table->date('approved_date')->nullable();
            $table->date('resched_date')->nullable();
            $table->text('resched_remarks')->nullable();
            $table->text('remarks')->nullable();
            $table->text('action')->nullable();
            $table->text('update_remarks')->nullable();
            $table->integer('days')->nullable();
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
        Schema::dropIfExists('document_acceptance');
    }
}
