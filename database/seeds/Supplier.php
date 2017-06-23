<?php

use Illuminate\Database\Seeder;

class Supplier extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Revlv\Settings\Suppliers\SupplierEloquent::insert([
            [
                "name"          => 'ACS DEVELOPMENT & PROPERTY MANAGERS INC',
                "owner"         => 'Timotei Martin C. Santiago',
                "address"       => '30 Muralla St., New Intramuros Village',
                'status'        => 'draft'
            ],
            [
                "name"          => '2174 Catering Service',
                "owner"         => 'Mercy Bapruga Agbayani',
                "address"       => '2174 Sobriedad Extension Sampaloc 053',
                'status'        => 'draft'
            ],
            [
                "name"          => '3 R Trading',
                "owner"         => 'Josefina Emberga Repalpa',
                "address"       => '715 Kalayaan St. San Antonio Cavite City',
                'status'        => 'draft'
            ]
        ]);
    }
}
