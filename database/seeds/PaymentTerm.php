<?php

use Illuminate\Database\Seeder;

class PaymentTerm extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Revlv\Settings\PaymentTerms\PaymentTermEloquent::insert([
            [
                "name"          => '7PDC',
                "description"       => '7 Days PDC'
            ],
            [
                "name"          => '15PDC',
                "description"       => '15 Days PDC'
            ],
            [
                "name"          => '30PDC',
                "description"       => '30 Days PDC'
            ],
            [
                "name"          => 'Cash',
                "description"       => 'Cash On Delivery'
            ],
            [
                "name"          => 'TT',
                "description"       => 'Teligraphic Transfer'
            ]
        ]);
    }
}
