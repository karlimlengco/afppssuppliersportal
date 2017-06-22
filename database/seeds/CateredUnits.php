<?php

use Illuminate\Database\Seeder;

class CateredUnits extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pcco = \Revlv\Settings\ProcurementCenters\ProcurementCenterEloquent::pluck('id')->toArray();
        \Revlv\Settings\CateredUnits\CateredUnitEloquent::insert([
            [
                "short_code"              => '101BDE',
                "description"       => '101st Infantry Brigade',
                'pcco_id'           =>  $pcco[array_rand($pcco)],
                'coa_address'       =>  'lorem'
            ],
            [
                "short_code"              => '101C0',
                "description"       => '101st Contracting Office',
                'pcco_id'           =>  $pcco[array_rand($pcco)],
                'coa_address'       =>  'lorem'
            ],
            [
                "short_code"              => '102BDE',
                "description"       => '102nd Infantry Brigade',
                'pcco_id'           =>  $pcco[array_rand($pcco)],
                'coa_address'       =>  'lorem'
            ]
        ]);
    }
}
