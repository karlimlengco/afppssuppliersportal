<?php

use Illuminate\Database\Seeder;
use App\Role;

class Roles extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $datas = collect([
            new Role(["name" => 'Admin', "slug" => 'admin', 'permissions' => [
                    'settings.*' =>  true,
                    'reports.*' =>  true,
                    'maintenance.*' =>  true,
                    'procurements.*' =>  true,
                    'bidding.*' =>  true,
                ]
            ]),
            new Role(["name" => 'End User', "slug" => 'end_user', 'permissions' => [
                    'procurements.*' =>  true,
                ]
            ]),
        ]);

        $datas->each(function($data) {
            $data->save();
        });
    }
}
