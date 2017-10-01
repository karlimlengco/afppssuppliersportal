<?php

use Illuminate\Database\Seeder;
use \Revlv\Settings\Suppliers\SupplierEloquent;
use Faker\Factory as Faker;

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
        $datas = collect([
            new SupplierEloquent(["id" => $faker->unique()->uuid, "name" => 'ACS DEVELOPMENT & PROPERTY MANAGERS INC', "owner" => 'Timotei Martin C. Santiago', "address" => '30 Muralla St., New Intramuros Village', 'status' => 'accepted']),
            new SupplierEloquent(["id" => $faker->unique()->uuid, "name" => '2174 Catering Service', "owner" => 'Mercy Bapruga Agbayani', "address" => '2174 Sobriedad Extension Sampaloc 053', 'status' => 'accepted']),
            new SupplierEloquent(["id" => $faker->unique()->uuid, "name" => '3 R Trading', "owner" => 'Josefina Emberga Repalpa', "address" => '715 Kalayaan St. San Antonio Cavite City', 'status' => 'accepted']),
        ]);

        $datas->each(function($data) {
            $data->save();
        });
    }
}
