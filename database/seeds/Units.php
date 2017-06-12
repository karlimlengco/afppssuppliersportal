<?php

use Illuminate\Database\Seeder;

class Units extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Revlv\Settings\Units\UnitEloquent::insert([
            [
                "name"              => 'NLC',
                "description"       => 'NLC'
            ],
            [
                "name"              => 'PF',
                "description"       => 'PF'
            ],
            [
                "name"              => 'NSSC',
                "description"       => 'NSSC'
            ]
        ]);
    }
}
