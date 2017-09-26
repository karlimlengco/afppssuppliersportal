<?php

use Illuminate\Database\Seeder;
use Revlv\Settings\Forms\PCCOHeader\PCCOHeaderEloquent;

class PCCOHeader extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $units =  \Revlv\Settings\ProcurementCenters\ProcurementCenterEloquent::get()->toArray();

        $inputs = [];
        foreach($units as $unit)
        {
            $inputs[]     =   new PCCOHeaderEloquent([
                "pcco_id" => $unit['id'],
                "content"   => "HEADQUARTERS <br>". $unit['name'] ."</br> <strong>Armed Forces of the Philippines Procurement Service</strong><br>". $unit['address']
                ]);
        }

        $datas = collect($inputs);

        $datas->each(function($data) {
            $data->save();
        });
    }
}
