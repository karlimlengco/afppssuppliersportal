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
            $table->integer('rfq_id')->nullable();
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
            $table->string('final_tax_amount')->nullable();
            $table->string('ewt_amount')->nullable();
            $table->string('or')->nullable();
            $table->string('payment_no')->nullable();
            $table->string('bank')->nullable();
            $table->string('payment_date')->nullable();

            $table->date('approval_date')->nullable();

            $table->integer('certified_by')->nullable();
            $table->integer('approver_id')->nullable();
            $table->integer('receiver_id')->nullable();

            $table->integer('days')->nullable();
            $table->text('remarks')->nullable();
            $table->integer('preaudit_days')->nullable();
            $table->text('preaudit_remarks')->nullable();
            $table->integer('jev_days')->nullable();
            $table->text('jev_remarks')->nullable();
            $table->integer('certify_days')->nullable();
            $table->text('certify_remarks')->nullable();
            $table->integer('check_days')->nullable();
            $table->text('check_remarks')->nullable();
            $table->integer('approved_days')->nullable();
            $table->text('approved_remarks')->nullable();
            $table->integer('released_days')->nullable();
            $table->text('released_remarks')->nullable();
            $table->integer('received_days')->nullable();
            $table->text('received_remarks')->nullable();
            $table->string('amount')->nullable();

            $table->text('action')->nullable();
            $table->text('preaudit_action')->nullable();
            $table->text('jev_action')->nullable();
            $table->text('certify_action')->nullable();
            $table->text('check_action')->nullable();
            $table->text('approved_action')->nullable();
            $table->text('released_action')->nullable();
            $table->text('received_action')->nullable();
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
