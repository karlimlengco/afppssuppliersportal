<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSuppliers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('name');
            $table->string('owner');
            $table->string('address');
            $table->string('tin')->nullable();

            $table->string('bank_id')->nullable();
            $table->string('branch')->nullable();
            $table->string('account_number')->nullable();
            $table->string('account_type')->nullable();

            $table->string('cell_1')->nullable();
            $table->string('cell_2')->nullable();
            $table->string('phone_1')->nullable();
            $table->string('phone_2')->nullable();
            $table->string('fax_1')->nullable();
            $table->string('fax_2')->nullable();
            $table->string('email_1')->nullable();
            $table->string('email_2')->nullable();

            $table->string('status')->default('draft');
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
        Schema::dropIfExists('suppliers');
    }
}
