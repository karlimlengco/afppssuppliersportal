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
        $datas = collect([
            new ProcurementTypeEloquent(["code" => 'GSM', "description"   => 'General Supplies & Materials']),
            new ProcurementTypeEloquent(["code" => 'SVCS', "description"   => 'Services']),
            new ProcurementTypeEloquent(["code" => 'GSMSVSC', "description"   => 'General Supplies & Services']),
        ]);

        $datas->each(function($data) {
            $data->save();
        });
    }
}
