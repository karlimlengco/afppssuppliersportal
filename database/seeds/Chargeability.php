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
            new ChargeabilityEloquent(["name" => 'MOOE', "description"   => 'Maintenance and Other Operating Expenses']),
            new ChargeabilityEloquent(["name" => 'CO', "description"   => 'Capital Outlay']),
            new ChargeabilityEloquent(["name" => 'TR', "description"   => 'Trust Receipts']),
            new ChargeabilityEloquent(["name" => 'UNRF', "description"   => 'UNRF']),
            new ChargeabilityEloquent(["name" => 'IATF', "description"   => 'IATF']),
            new ChargeabilityEloquent(["name" => 'SPAPS', "description"   => 'SPAPS']),
        ]);

        $datas->each(function($data) {
            $data->save();
        });
    }
}
