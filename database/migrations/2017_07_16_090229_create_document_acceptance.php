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
            $table->uuid('id');
            $table->string('upr_id');
            $table->string('bac_id');
            $table->string('upr_number')->nullable();
            $table->string('ref_number')->nullable();
            $table->date('pre_proc_date')->nullable();
            $table->date('approved_date')->nullable();
            $table->date('return_date')->nullable();
            $table->text('return_remarks')->nullable();
            $table->text('remarks')->nullable();
            $table->text('action')->nullable();
            $table->text('update_remarks')->nullable();
            $table->integer('days')->nullable();
            $table->string('processed_by')->nullable();
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
