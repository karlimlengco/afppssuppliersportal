<?php

use Illuminate\Database\Seeder;
use \Revlv\Settings\Holidays\HolidayEloquent;
use Faker\Factory as Faker;

class Holidays extends Seeder
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
            new HolidayEloquent(["id" => $faker->unique()->uuid, "name" => 'Birthday of Manuel Quezon', "holiday_date"   => '2017-08-19']),
            new HolidayEloquent(["id" => $faker->unique()->uuid, "name" => 'National Heroes Day', "holiday_date"   => '2017-08-28']),
            new HolidayEloquent(["id" => $faker->unique()->uuid, "name" => 'Ninoy Aquino Day',  "holiday_date" => '2017-08-21']),
            new HolidayEloquent(["id" => $faker->unique()->uuid, "name" => 'Eid al Adha in 2017', "holiday_date" => '2017-09-01']),
            new HolidayEloquent(["id" => $faker->unique()->uuid, "name" => 'All Saints` Day', "holiday_date" => '2017-11-01']),
            new HolidayEloquent(["id" => $faker->unique()->uuid, "name" => 'All Saints` Day', "holiday_date" => '2017-11-01']),
            new HolidayEloquent(["id" => $faker->unique()->uuid, "name" => 'Bonifacio Day', "holiday_date" => '2017-11-30']),
            new HolidayEloquent(["id" => $faker->unique()->uuid, "name" => 'Christmas Day',  "holiday_date" => '2017-12-25']),
            new HolidayEloquent(["id" => $faker->unique()->uuid, "name" => 'Rizal Day', "holiday_date" => '2017-12-30']),
            new HolidayEloquent(["id" => $faker->unique()->uuid, "name" => 'New Year`s Eve', "holiday_date" => '2017-12-31']),
        ]);

        $datas->each(function($data) {
            $data->save();
        });
    }
}
