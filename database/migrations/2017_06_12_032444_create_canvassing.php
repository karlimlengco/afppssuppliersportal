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
            $table->string('rfq_number')->nullable();
            $table->string('upr_number')->nullable();
            $table->date('canvass_date');
            $table->time('adjourned_time')->nullable();
            $table->time('closebox_time')->nullable();
            $table->time('order_time')->nullable();
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