<?php

use Illuminate\Database\Seeder;
use Revlv\Settings\Forms\Header\HeaderEloquent;

class HeaderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $units =  \Revlv\Settings\CateredUnits\CateredUnitEloquent::get()->toArray();

        $inputs = [];
        foreach($units as $unit)
        {
            $inputs[]     =   new HeaderEloquent([
                "unit_id" => $unit['id'],
                "content"   => "HEADQUARTERS <br>". $unit['description'] ."</br> <strong>Armed Forces of the Philippines Procurement Service</strong><br> Camp General Emilio Aguinaldo, Quezon City"
                ]);
        }

        $datas = collect($inputs);

        $datas->each(function($data) {
            $data->save();
        });
    }
}
