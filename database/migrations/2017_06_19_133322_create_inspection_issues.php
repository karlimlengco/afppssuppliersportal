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
            $table->uuid('id');
            $table->string('inspection_id');
            $table->string('issue');
            $table->text('remarks')->nullable();
            $table->string('is_corrected')->nullable();
            $table->string('prepared_by');
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
