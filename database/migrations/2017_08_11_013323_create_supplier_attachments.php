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
            $table->uuid('id');
            $table->string('supplier_id');
            $table->string('name');
            $table->string('ref_number')->nullable();
            $table->string('place')->nullable();
            $table->string('file_name');
            $table->string('user_id');
            $table->string('type');
            $table->date('issued_date')->nullable();
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
