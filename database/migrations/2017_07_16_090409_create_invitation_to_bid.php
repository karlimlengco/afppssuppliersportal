<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvitationToBid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invitation_to_bid', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('upr_id');
            $table->string('upr_number')->nullable();
            $table->string('ref_number')->nullable();
            $table->date('transaction_date');
            $table->text('update_remarks')->nullable();
            $table->date('approved_date')->nullable();
            $table->string('approved_by')->nullable();
            $table->text('action')->nullable();
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('invitation_to_bid');
    }
}
