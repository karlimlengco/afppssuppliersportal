<?php

use Illuminate\Database\Seeder;

class Units extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pcco = \Revlv\Settings\ProcurementCenters\ProcurementCenterEloquent::pluck('id')->toArray();
        \Revlv\Settings\Units\UnitEloquent::insert([
            [
                "name"              => 'NLC',
                "description"       => 'NLC',
                'pcco_id'           =>  $pcco[array_rand($pcco)],
                'coa_address'       =>  'lorem'
            ],
            [
                "name"              => 'PF',
                "description"       => 'PF',
                'pcco_id'           =>  $pcco[array_rand($pcco)],
                'coa_address'       =>  'lorem'
            ],
            [
                "name"              => 'NSSC',
                "description"       => 'NSSC',
                'pcco_id'           =>  $pcco[array_rand($pcco)],
                'coa_address'       =>  'lorem'
            ]
        ]);
    }
}
