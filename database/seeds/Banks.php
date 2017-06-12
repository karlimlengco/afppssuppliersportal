<?php

use Illuminate\Database\Seeder;

class Banks extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Revlv\Settings\Banks\BankEloquent::insert([
            [
                "code"              => 'BPI',
                "description"       => 'BPI'
            ],
            [
                "code"              => 'UPCB',
                "description"       => 'United Coconut Planters Bank'
            ],
            [
                "code"              => 'AUB',
                "description"       => 'Asia United Bank'
            ],
            [
                "code"              => 'CHINABANK',
                "description"       => 'China Banking Corporation'
            ],
            [
                "code"              => 'CITIBANK',
                "description"       => 'CITIBANK'
            ]
        ]);
    }
}
