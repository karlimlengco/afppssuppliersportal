<?php

use Illuminate\Database\Seeder;
use \Revlv\Settings\Chargeability\ChargeabilityEloquent;

class Chargeability extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $faker = \Faker\Factory::create();
        $datas = collect([
            new ChargeabilityEloquent(["id" => $faker->unique()->uuid, "name" => 'MOOE', "description"   => 'Maintenance and Other Operating Expenses']),
            new ChargeabilityEloquent(["id" => $faker->unique()->uuid, "name" => 'CO', "description"   => 'Capital Outlay']),
            new ChargeabilityEloquent(["id" => $faker->unique()->uuid, "name" => 'TR', "description"   => 'Trust Receipts']),
            new ChargeabilityEloquent(["id" => $faker->unique()->uuid, "name" => 'UNRF', "description"   => 'UNRF']),
            new ChargeabilityEloquent(["id" => $faker->unique()->uuid, "name" => 'IATF', "description"   => 'IATF']),
            new ChargeabilityEloquent(["id" => $faker->unique()->uuid, "name" => 'SPAPS', "description"   => 'SPAPS']),
        ]);

        $datas->each(function($data) {
            $data->save();
        });
    }
}
