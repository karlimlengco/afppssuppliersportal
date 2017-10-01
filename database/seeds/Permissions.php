<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Revlv\Sentinel\Permissions\PermissionEloquent;

class Permissions extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker =  Faker::create();
        $datas = collect([
            new PermissionEloquent([
              "id" => $faker->unique()->uuid, "permission" => 'settings.*', "description" => 'Settings']),
            new PermissionEloquent([
              "id" => $faker->unique()->uuid, "permission" => 'reports.*', "description" => 'Reports']),
            new PermissionEloquent([
              "id" => $faker->unique()->uuid, "permission" => 'maintenance.*', "description" => 'Maintenance']),
            new PermissionEloquent([
              "id" => $faker->unique()->uuid, "permission" => 'procurements.*', "description" => 'Alternatice Mode of Procurement']),
            new PermissionEloquent([
              "id" => $faker->unique()->uuid, "permission" => 'biddings.*', "description" => 'Competitive Bidding']),
            new PermissionEloquent([
              "id" => $faker->unique()->uuid, "permission" => 'library.*', "description" => 'Library'])
        ]);

        $datas->each(function($data) {
            $data->save();
        });
    }
}
