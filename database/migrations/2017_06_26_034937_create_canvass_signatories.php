<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCanvassSignatories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('canvass_signatories', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('canvass_id');
            $table->string('signatory_id');
            $table->integer('is_present')->nullable();
            $table->integer('cop')->nullable();
            $table->integer('rop')->nullable();
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
        Schema::dropIfExists('canvass_signatories');
    }
}
