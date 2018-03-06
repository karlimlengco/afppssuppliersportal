<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertLdapIntoVouchers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vouchers', function($table)
        {
            $table->date('prepare_cheque_date')->nullable();
            $table->date('counter_sign_date')->nullable();
            $table->text('prepare_cheque_remarks')->nullable();
            $table->string('prepare_cheque_days')->nullable();
            $table->text('prepare_cheque_action')->nullable();
            $table->text('counter_sign_remarks')->nullable();
            $table->text('counter_sign_action')->nullable();
            $table->string('counter_sign_days')->nullable();
        });
    }

}
