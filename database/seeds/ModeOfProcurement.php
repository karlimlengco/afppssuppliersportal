<?php

use Illuminate\Database\Seeder;
use \Revlv\Settings\ModeOfProcurements\ModeOfProcurementEloquent;

class ModeOfProcurement extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $modes = collect([
            new ModeOfProcurementEloquent(["name" => 'Limited Source Bidding', "description"   => 'Limited Source Bidding']),
            new ModeOfProcurementEloquent(["name" => 'Direct Contracting', "description"   => 'Direct Contracting']),
            new ModeOfProcurementEloquent(["name" => 'Repeat Order', "description"   => 'Repeat Order']),
            new ModeOfProcurementEloquent(["name" => 'Shopping', "description"   => 'Shopping']),
            new ModeOfProcurementEloquent(["name" => 'Negotiated', "description"   => 'Negotiated'])
        ]);

        $modes->each(function($mode) {
            $mode->save();
        });
    }

}
