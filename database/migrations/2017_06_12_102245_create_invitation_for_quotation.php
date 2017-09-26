<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvitationForQuotation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invitation_for_quotation', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('signatory_id');
            $table->text('signatory_text')->nullable();
            $table->text('venue');
            $table->date('transaction_date');
            $table->text('action')->nullable();
            $table->text('remarks')->nullable();
            $table->text('update_remarks')->nullable();
            $table->integer('days')->nullable();
            $table->string('prepared_by')->nullable();
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
        Schema::dropIfExists('invitation_for_quotation');
    }
}
