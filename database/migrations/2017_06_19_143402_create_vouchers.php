<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVouchers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('upr_id');
            $table->integer('rfq_id');
            $table->integer('prepared_by')->nullable();
            $table->string('rfq_number')->nullable();
            $table->string('upr_number')->nullable();
            $table->date('transaction_date');
            $table->string('bir_address');
            $table->string('final_tax')->nullable();
            $table->string('expanded_witholding_tax')->nullable();
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
        Schema::dropIfExists('vouchers');
    }
}
