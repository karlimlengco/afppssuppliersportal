<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountCodes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_codes', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('name');
            $table->integer('expense_class_id')->nullable();
            $table->string('old_account_code')->nullable();
            $table->string('new_account_code');
            $table->smallInteger('main_class')->nullable();
            $table->smallInteger('sub_class')->nullable();
            $table->smallInteger('account_group')->nullable();
            $table->smallInteger('detailed_account')->nullable();
            $table->smallInteger('contra_account')->nullable();
            $table->smallInteger('sub_account')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->unique(['old_account_code', 'new_account_code']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_codes');
    }
}
