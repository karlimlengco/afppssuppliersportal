<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNoticeOfAwards extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notice_of_awards', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('canvass_id');
            $table->integer('rfq_id');
            $table->integer('upr_id');
            $table->string('rfq_number')->nullable();
            $table->string('upr_number')->nullable();
            $table->integer('signatory_id')->nullable();
            $table->integer('proponent_id');
            $table->integer('awarded_by')->nullable();
            $table->integer('seconded_by')->nullable();
            $table->timestamp('awarded_date')->nullable();
            $table->string('status')->default('pending');
            $table->text('remarks')->nullable();
            $table->text('update_remarks')->nullable();
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
        Schema::dropIfExists('notice_of_awards');
    }
}
