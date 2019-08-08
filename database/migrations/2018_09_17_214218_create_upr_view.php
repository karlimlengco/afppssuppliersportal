<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUprView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            CREATE ALGORITHM = MERGE VIEW  upr_view
            as
            select
              unit_purchase_requests.id as upr_id,
              unit_purchase_requests.id as count_id,
              unit_purchase_requests.upr_number,
              unit_purchase_requests.total_amount,
              unit_purchase_requests.procurement_office,
              unit_purchase_requests.units,
              unit_purchase_requests.status,
              unit_purchase_requests.date_processed,
              unit_purchase_requests.mode_of_procurement,
              unit_purchase_requests.next_due,
              unit_purchase_requests.state,
              unit_purchase_requests.completed_at,
              pc.name as pcco,
              pc.programs,
              catered_units.short_code,
              po.bid_amount
            from unit_purchase_requests
              left join procurement_centers as pc
                on unit_purchase_requests.procurement_office  = pc.id
              LEFT JOIN catered_units ON catered_units.pcco_id = pc.id
              AND catered_units.id = unit_purchase_requests.units
              left join purchase_orders as po
                on unit_purchase_requests.id  = po.upr_id
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('upr_view');
    }
}
