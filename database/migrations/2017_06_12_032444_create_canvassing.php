    <?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCanvassing extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('canvassing', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('rfq_id');
            $table->integer('upr_id');
            $table->string('rfq_number')->nullable();
            $table->string('upr_number')->nullable();
            $table->date('canvass_date');
            $table->time('canvass_time')->nullable();
            $table->time('adjourned_time')->nullable();
            $table->time('closebox_time')->nullable();
            $table->time('order_time')->nullable();
            $table->string('signatory_id')->nullable();
            $table->string('open_by')->nullable();
            $table->string('update_remarks')->nullable();
            $table->integer('days')->nullable();
            $table->text('remarks')->nullable();
            $table->text('action')->nullable();
            $table->text('resolution')->nullable();
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
        Schema::dropIfExists('canvassing');
    }
}
