<?php

use Illuminate\Database\Seeder;

class UPR extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestEloquent::class, 50)->create();
    }
}
