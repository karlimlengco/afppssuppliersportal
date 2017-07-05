<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVouchers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('upr_id');
            $table->integer('rfq_id');
            $table->integer('prepared_by')->nullable();
            $table->string('rfq_number')->nullable();
            $table->string('upr_number')->nullable();
            $table->date('transaction_date');
            $table->date('payment_release_date')->nullable();
            $table->date('payment_received_date')->nullable();
            $table->integer('process_releaser')->nullable();
            $table->integer('payment_receiver')->nullable();
            $table->string('bir_address');
            $table->string('final_tax')->nullable();
            $table->string('expanded_witholding_tax')->nullable();

            $table->date('preaudit_date')->nullable();

            $table->date('certify_date')->nullable();
            $table->integer('is_cash_avail')->nullable();
            $table->integer('subject_to_authority_to_debit_acc')->nullable();
            $table->integer('documents_completed')->nullable();

            $table->date('journal_entry_date')->nullable();
            $table->string('journal_entry_number')->nullable();
            $table->string('update_remarks')->nullable();
            $table->string('or')->nullable();

            $table->date('approval_date')->nullable();

            $table->integer('certified_by')->nullable();
            $table->integer('approver_id')->nullable();
            $table->integer('receiver_id')->nullable();


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
        Schema::dropIfExists('vouchers');
    }
}
