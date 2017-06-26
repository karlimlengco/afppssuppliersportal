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
            $table->timestamp('awarded_date')->nullable();
            $table->text('remarks')->nullable();
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
