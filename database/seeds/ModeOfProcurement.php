<?php

use Illuminate\Database\Seeder;

class ModeOfProcurement extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Revlv\Settings\ModeOfProcurements\ModeOfProcurementEloquent::insert([
            [
                "name"          => 'Limited Source Bidding',
                "description"   => 'Limited Source Bidding'
            ],
            [
                "name"          => 'Direct Contracting',
                "description"   => 'Direct Contracting'
            ],
            [
                "name"          => 'Repeat Order',
                "description"   => 'Repeat Order'
            ],
            [
                "name"          => 'Shopping',
                "description"   => 'Shopping'
            ],
            [
                "name"          => 'Negotiated',
                "description"   => 'Negotiated'
            ],
        ]);
    }
}
