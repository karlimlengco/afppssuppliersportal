<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCateredUnits extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catered_units', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('pcco_id');
            $table->string('short_code');
            $table->text('description');
            $table->text('coa_address');
            $table->text('coa_address_2')->nullable();
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
        Schema::dropIfExists('catered_units');
    }
}
