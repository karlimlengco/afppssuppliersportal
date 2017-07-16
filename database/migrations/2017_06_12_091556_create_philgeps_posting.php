<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhilgepsPosting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('philgeps_posting', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('rfq_id')->nullalble();
            $table->integer('upr_id');
            $table->string('philgeps_number');
            $table->string('upr_number')->nullable();
            $table->string('rfq_number')->nullable();
            $table->date('transaction_date');
            $table->date('philgeps_posting');
            $table->date('deadline_rfq')->nullable();
            $table->string('opening_time')->nullable();
            $table->string('update_remarks')->nullable();
            $table->string('remarks')->nullable();
            $table->string('newspaper')->nullable();
            $table->string('action')->nullable();
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
        Schema::dropIfExists('philgeps_posting');
    }
}
