<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhilgepsAttachments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('philgeps_attachments', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('philgeps_id');
            $table->string('name');
            $table->string('file_name');
            $table->string('user_id');
            $table->date('upload_date');
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
        Schema::dropIfExists('philgeps_attachments');
    }
}
