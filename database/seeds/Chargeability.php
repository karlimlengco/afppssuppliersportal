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

        $datas = collect([
            new ChargeabilityEloquent(["name" => 'MOOE', "description"   => 'Lorem Ipsum']),
        ]);

        $datas->each(function($data) {
            $data->save();
        });
    }
}
