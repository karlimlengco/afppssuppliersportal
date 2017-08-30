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
        $datas = collect([
            new PaymentTermEloquent(["name" => 'OTP', "description"   => 'One Time Payment']),
            new PaymentTermEloquent(["name" => 'TT', "description"   => 'Teligraphic Transfer']),
        ]);

        $datas->each(function($data) {
            $data->save();
        });
    }
}
