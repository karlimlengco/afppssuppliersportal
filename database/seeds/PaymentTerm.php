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
            new PaymentTermEloquent(["name" => '7PDC', "description"   => '7 Days PDC']),
            new PaymentTermEloquent(["name" => '15PDC', "description"   => '15 Days PDC']),
            new PaymentTermEloquent(["name" => '30PDC', "description"   => '30 Days PDC']),
            new PaymentTermEloquent(["name" => 'Cash', "description"   => 'Cash On Delivery']),
            new PaymentTermEloquent(["name" => 'TT', "description"   => 'Teligraphic Transfer']),
        ]);

        $datas->each(function($data) {
            $data->save();
        });
    }
}
