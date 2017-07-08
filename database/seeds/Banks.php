<?php

use Illuminate\Database\Seeder;
use \Revlv\Settings\Banks\BankEloquent;

class Banks extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $datas = collect([
            new BankEloquent(["code" => 'BPI', "description"   => 'BPI']),
            new BankEloquent(["code" => 'UPCB', "description"   => 'United Coconut Planters Bank']),
            new BankEloquent(["code" => 'AUB', "description"   => 'Asia United Bank']),
            new BankEloquent(["code" => 'CHINABANK', "description"   => 'China Banking Corporation']),
            new BankEloquent(["code" => 'CITIBANK', "description"   => 'CITIBANK'])
        ]);

        $datas->each(function($data) {
            $data->save();
        });
    }
}
