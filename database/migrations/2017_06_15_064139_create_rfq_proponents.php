<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRfqProponents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rfq_proponents', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('rfq_id');
            $table->integer('proponents');
            $table->string('note')->nullable();
            $table->date('date_processed');
            $table->integer('prepared_by');
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
        Schema::dropIfExists('rfq_proponents');
    }
}
