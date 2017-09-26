<?php

use Illuminate\Database\Seeder;
use \Revlv\Settings\BacSec\BacSecEloquent;
use Faker\Factory as Faker;

class BacSec extends Seeder
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
            new BacSecEloquent(["id" => $faker->unique()->uuid, "name" => 'GHQ SBAC1', "description"   => 'GHQ SBAC1']),
            new BacSecEloquent(["id" => $faker->unique()->uuid, "name" => 'GHQ SBAC2', "description"   => 'GHQ SBAC2']),
            new BacSecEloquent(["id" => $faker->unique()->uuid, "name" => 'PA SBAC1', "description"   => 'PA SBAC1']),
            new BacSecEloquent(["id" => $faker->unique()->uuid, "name" => 'PA SBAC2', "description"   => 'PA SBAC2']),
            new BacSecEloquent(["id" => $faker->unique()->uuid, "name" => 'MID SBAC (PA)', "description"   => 'MID SBAC (PA)']),
            new BacSecEloquent(["id" => $faker->unique()->uuid, "name" => 'PAF SBAC', "description"   => 'PAF SBAC']),
            new BacSecEloquent(["id" => $faker->unique()->uuid, "name" => 'PAF BAC', "description"   => 'PAF BAC']),
            new BacSecEloquent(["id" => $faker->unique()->uuid, "name" => 'PA BAC', "description"   => 'PA BAC']),
            new BacSecEloquent(["id" => $faker->unique()->uuid, "name" => 'PN BAC', "description"   => 'PN BAC']),
            new BacSecEloquent(["id" => $faker->unique()->uuid, "name" => 'GHQ BAC 1', "description"   => 'GHQ BAC 1']),
            new BacSecEloquent(["id" => $faker->unique()->uuid, "name" => 'GHQ BAC 2', "description"   => 'GHQ BAC 2']),
        ]);

        $datas->each(function($data) {
            $data->save();
        });
    }
}
