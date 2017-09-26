<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUnitsAttachments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('unit_attachments', function (Blueprint $table) {
            $table->uuid('id');
            $table->integer('unit_id');
            $table->string('name');
            $table->string('file_name');
            $table->integer('user_id');
            $table->date('validity_date');
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
        Schema::dropIfExists('unit_attachments');
    }
}
