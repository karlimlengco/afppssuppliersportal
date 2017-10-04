<?php

use Illuminate\Database\Seeder;
use \Revlv\Settings\Suppliers\SupplierEloquent;
use Faker\Factory as Faker;
use League\Csv\Reader;


class Supplier extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        $reader = Reader::createFromPath(database_path().'/csv/suppliers.csv');
        $reader->each(function($row, $rowOffset)use ($faker) {
            if($row[0] != null){
              SupplierEloquent::create([
                  'id' => $faker->unique()->uuid,
                  'name' => $row[0],
                  'owner' => $row[2],
                  'address' => $row[3],
                  'tin' => $row[5],
                  'email_1' => $row[8],
                  'email_2' => $row[9],
                  'cell_1' => $row[10],
                  'cell_2' => $row[11],
                  'phone_1' => $row[12],
                  'phone_2' => $row[13],
                  'fax_1' => $row[14],
                  'fax_2' => $row[15],
                  'status' => 'accepted',
              ]);
            }
            return true;
        });

    }
}
