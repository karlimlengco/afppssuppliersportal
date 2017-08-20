<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIspqQuotations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ispq_quotations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ispq_id');
            $table->integer('rfq_id');
            $table->integer('upr_id');
            $table->string('description');
            $table->string('total_amount')->nullable();
            $table->date('canvassing_date')->nullable();
            $table->time('canvassing_time')->nullable();
            $table->string('upr_number')->nullable();
            $table->string('rfq_number')->nullable();
            $table->string('delay_count')->nullable();
            $table->text('remarks')->nullable();
            $table->text('action')->nullable();
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
        Schema::dropIfExists('ispq_quotations');
    }
}
