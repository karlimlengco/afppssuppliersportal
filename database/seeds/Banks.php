<?php

use Illuminate\Database\Seeder;
use \Revlv\Settings\Banks\BankEloquent;
use Faker\Factory as Faker;

class Banks extends Seeder
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
            new BankEloquent(["id" => $faker->unique()->uuid, "code" => 'BPI', "description"   => 'BPI']),
            new BankEloquent(["id" => $faker->unique()->uuid, "code" => 'UPCB', "description"   => 'United Coconut Planters Bank']),
            new BankEloquent(["id" => $faker->unique()->uuid, "code" => 'AUB', "description"   => 'Asia United Bank']),
            new BankEloquent(["id" => $faker->unique()->uuid, "code" => 'CHINABANK', "description"   => 'China Banking Corporation']),
            new BankEloquent(["id" => $faker->unique()->uuid, "code" => 'CITIBANK', "description"   => 'CITIBANK'])
        ]);

        $datas->each(function($data) {
            $data->save();
        });
    }
}
