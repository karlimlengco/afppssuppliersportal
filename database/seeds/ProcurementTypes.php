<?php

use Illuminate\Database\Seeder;
use \Revlv\Settings\ProcurementTypes\ProcurementTypeEloquent;

class ProcurementTypes extends Seeder
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
            new ProcurementTypeEloquent(['id' => $faker->unique()->uuid, "code" => 'GSM', "description"   => 'General Supplies & Materials']),
            new ProcurementTypeEloquent(['id' => $faker->unique()->uuid, "code" => 'SVCS', "description"   => 'Services']),
            new ProcurementTypeEloquent(['id' => $faker->unique()->uuid, "code" => 'GSMSVSC', "description"   => 'General Supplies & Services']),
        ]);

        $datas->each(function($data) {
            $data->save();
        });
    }
}
