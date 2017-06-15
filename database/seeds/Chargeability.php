<?php

use Illuminate\Database\Seeder;

class Chargeability extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Revlv\Settings\Chargeability\ChargeabilityEloquent::insert([
            [
                "name"          => 'MOOE',
                "description"   => 'Lorem Ipsum'
            ]
        ]);
    }
}
