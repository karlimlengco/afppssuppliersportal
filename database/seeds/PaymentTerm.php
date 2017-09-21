<?php

use Illuminate\Database\Seeder;
use \Revlv\Settings\PaymentTerms\PaymentTermEloquent;

class PaymentTerm extends Seeder
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
            new PaymentTermEloquent(['id' => $faker->unique()->uuid, "name" => 'OTP', "description"   => 'One Time Payment']),
            new PaymentTermEloquent(['id' => $faker->unique()->uuid, "name" => 'TT', "description"   => 'Teligraphic Transfer']),
        ]);

        $datas->each(function($data) {
            $data->save();
        });
    }
}
