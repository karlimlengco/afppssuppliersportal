<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class Units extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $pcco = \Revlv\Settings\ProcurementCenters\ProcurementCenterEloquent::pluck('id')->toArray();
        \Revlv\Settings\Units\UnitEloquent::insert([
            [
                "id"                => $faker->unique()->uuid,
                "name"              => 'NLC',
                "description"       => 'NLC',
                'pcco_id'           =>  $pcco[array_rand($pcco)],
                'coa_address'       =>  'lorem'
            ],
            [
                "id"                => $faker->unique()->uuid,
                "name"              => 'PF',
                "description"       => 'PF',
                'pcco_id'           =>  $pcco[array_rand($pcco)],
                'coa_address'       =>  'lorem'
            ],
            [
                "id"                => $faker->unique()->uuid,
                "name"              => 'NSSC',
                "description"       => 'NSSC',
                'pcco_id'           =>  $pcco[array_rand($pcco)],
                'coa_address'       =>  'lorem'
            ]
        ]);
    }
}
