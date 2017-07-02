<?php

use Illuminate\Database\Seeder;

class Holidays extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Revlv\Settings\Holidays\HolidayEloquent::insert([
            [
                "name"              => 'Birthday of Manuel Quezon',
                "holiday_date"      => '2017-08-19',
            ],
            [
                "name"              => 'National Heroes Day',
                "holiday_date"      => '2017-08-28',
            ],
            [
                "name"              => 'Ninoy Aquino Day',
                "holiday_date"      => '2017-08-21',
            ],
            [
                "name"              => 'Eid al Adha in 2017',
                "holiday_date"      => '2017-09-01',
            ],
            [
                "name"              => 'All Saints` Day',
                "holiday_date"      => '2017-11-01',
            ],
            [
                "name"              => 'Bonifacio Day',
                "holiday_date"      => '2017-11-30',
            ],
            [
                "name"              => 'Christmas Day',
                "holiday_date"      => '2017-12-25',
            ],
            [
                "name"              => 'Rizal Day',
                "holiday_date"      => '2017-12-30',
            ],
            [
                "name"              => 'New Year`s Eve',
                "holiday_date"      => '2017-12-31',
            ]
        ]);
    }
}
