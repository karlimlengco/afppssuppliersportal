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
            $table->uuid('id');
            $table->string('rfq_id');
            $table->string('upr_id');
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
            $table->string('presiding_officer')->nullable();
            $table->string('chief')->nullable();
            $table->text('other_attendees')->nullable();

            $table->text('chief_signatory')->nullable();
            $table->text('presiding_signatory')->nullable();
            $table->text('unit_head_signatory')->nullable();
            $table->text('mfo_signatory')->nullable();
            $table->text('legal_signatory')->nullable();
            $table->text('secretary_signatory')->nullable();

            $table->string('unit_head')->nullable();
            $table->string('mfo')->nullable();
            $table->string('legal')->nullable();
            $table->string('secretary')->nullable();

            $table->integer('unit_head_attendance')->nullable();
            $table->integer('mfo_attendance')->nullable();
            $table->integer('legal_attendance')->nullable();
            $table->integer('secretary_attendance')->nullable();
            $table->integer('chief_attendance')->nullable();
            $table->integer('cop')->nullable();
            $table->integer('rop')->nullable();

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
