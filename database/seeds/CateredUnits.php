<?php

use Illuminate\Database\Seeder;
use League\Csv\Reader;

class CateredUnits extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $reader = Reader::createFromPath(database_path().'/csv/pcco_units.csv');
        $reader->each(function($row, $rowOffset) {
            \Revlv\Settings\CateredUnits\CateredUnitEloquent::create([
                'short_code' => $row[1],
                'description' => $row[2],
                'pcco_id' => $row[0],
                'coa_address' => "lorem Ipsum dolor sit"
            ]);
            return true;
        });
    }
}
