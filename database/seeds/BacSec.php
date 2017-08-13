<?php

use Illuminate\Database\Seeder;
use \Revlv\Settings\BacSec\BacSecEloquent;

class BacSec extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $datas = collect([
            new BacSecEloquent(["name" => 'GHQ SBAC1', "description"   => 'GHQ SBAC1']),
            new BacSecEloquent(["name" => 'GHQ SBAC2', "description"   => 'GHQ SBAC2']),
            new BacSecEloquent(["name" => 'PA SBAC1', "description"   => 'PA SBAC1']),
            new BacSecEloquent(["name" => 'PA SBAC2', "description"   => 'PA SBAC2']),
            new BacSecEloquent(["name" => 'MID SBAC (PA)', "description"   => 'MID SBAC (PA)']),
            new BacSecEloquent(["name" => 'PAF SBAC', "description"   => 'PAF SBAC']),
            new BacSecEloquent(["name" => 'PAF BAC', "description"   => 'PAF BAC']),
            new BacSecEloquent(["name" => 'PA BAC', "description"   => 'PA BAC']),
            new BacSecEloquent(["name" => 'PN BAC', "description"   => 'PN BAC']),
            new BacSecEloquent(["name" => 'GHQ BAC 1', "description"   => 'GHQ BAC 1']),
            new BacSecEloquent(["name" => 'GHQ BAC 2', "description"   => 'GHQ BAC 2']),
        ]);

        $datas->each(function($data) {
            $data->save();
        });
    }
}
