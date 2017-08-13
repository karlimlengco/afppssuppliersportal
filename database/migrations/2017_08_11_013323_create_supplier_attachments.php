<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupplierAttachments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_attachments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('supplier_id');
            $table->string('name');
            $table->string('file_name');
            $table->integer('user_id');
            $table->date('validity_date')->nullable();
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
        Schema::dropIfExists('supplier_attachments');
    }
}
