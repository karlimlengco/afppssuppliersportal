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
            new BacSecEloquent(["name" => 'PN BAC', "description"   => 'PN BAC']),
            new BacSecEloquent(["name" => 'PA BAC', "description"   => 'PA BAC']),
            new BacSecEloquent(["name" => 'PAF BAC', "description"   => 'PAF BAC']),
        ]);

        $datas->each(function($data) {
            $data->save();
        });
    }
}
