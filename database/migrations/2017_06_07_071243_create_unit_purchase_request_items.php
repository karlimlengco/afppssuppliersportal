<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUnitPurchaseRequestItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('unit_purchase_request_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('upr_id');
            $table->string('item_description');
            $table->string('quantity');
            $table->string('unit_measurement');
            $table->string('unit_price');
            $table->string('total_amount');
            $table->string('upr_number')->nullable();
            $table->string('ref_number')->nullable();
            $table->string('new_account_code')->nullable();
            $table->string('type')->nullable();
            $table->integer('prepared_by')->nullable();
            $table->date('date_prepared')->nullable();

            $table->timestamps();

            $table->index('upr_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('unit_purchase_request_items');
    }
}
