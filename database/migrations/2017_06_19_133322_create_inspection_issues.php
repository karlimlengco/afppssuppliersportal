<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInspectionIssues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inspection_issues', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('inspection_id');
            $table->string('issue');
            $table->string('remarks')->nullable();
            $table->string('is_corrected')->nullable();
            $table->integer('prepared_by');
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
        Schema::dropIfExists('inspection_issues');
    }
}
