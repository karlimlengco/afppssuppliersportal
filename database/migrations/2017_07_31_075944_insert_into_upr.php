<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertIntoUpr extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('unit_purchase_requests', function($table)
        {
            $table->date('next_due')->nullable();
            $table->text('next_step')->nullable();
        });


        Schema::table('purchase_orders', function($table)
        {
            $table->text('notes')->nullable();
        });
    }

}
