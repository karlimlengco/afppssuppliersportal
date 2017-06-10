<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUnitPurchaseRequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('unit_purchase_requests', function (Blueprint $table) {
            $table->increments('id');

            $table->string('place_of_delivery');
            $table->string('mode_of_procurement');
            $table->string('chargability');
            $table->string('account_code');
            $table->string('fund_validity')->nullable();
            $table->string('terms_of_payment')->nullable();
            $table->string('other_infos')->nullable();
            $table->string('upr_number');
            $table->text('purpose');
            $table->string('afpps_ref_number')->nullable();
            $table->string('date_prepared');
            $table->string('total_amount');
            $table->integer('prepared_by');

            $table->timestamps();

            $table->index('account_code');
            $table->index('upr_number');
            $table->index('afpps_ref_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('unit_purchase_requests');
    }
}
