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
            $table->string('chargeability');
            $table->string('account_code');

            $table->string('fund_validity')->nullable();
            $table->string('terms_of_payment')->nullable();
            $table->string('other_infos')->nullable();

            $table->string('units');
            $table->text('purpose');

            $table->string('project_name')->nullable();
            $table->string('upr_number')->nullable();
            $table->string('ref_number')->nullable();

            $table->date('date_prepared');
            $table->date('date_processed')->nullable();

            $table->string('total_amount');

            $table->string('status')->default('draft');

            $table->integer('prepared_by');

            $table->timestamps();

            $table->index('account_code');
            $table->index('upr_number');
            $table->index('ref_number');
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
