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
                    'biddings.*' =>  true,
                    'library.*' =>  true,
                ]
            ]),
            new Role(["name" => 'PCCO Admin', "slug" => 'pcco_admin', 'permissions' => [
                    'procurements.*' =>  true,
                    'biddings.*' =>  true,
                    'reports.*' =>  true,
                    'library.*' =>  true,
                ]
            ]),
            new Role(["name" => 'PCCO Operation', "slug" => 'pcco_operation', 'permissions' => [
                    'procurements.*' =>  true,
                    'biddings.*' =>  true,
                ]
            ]),
            new Role(["name" => 'BAC Admin', "slug" => 'bac_admin', 'permissions' => [
                    'procurements.*' =>  true,
                    'biddings.*' =>  true,
                    'reports.*' =>  true,
                    'library.*' =>  true,
                ]
            ]),
            new Role(["name" => 'BAC Operation', "slug" => 'bac_operation', 'permissions' => [
                    'procurements.*' =>  true,
                    'biddings.*' =>  true,
                ]
            ])
        ]);

        $datas->each(function($data) {
            $data->save();
        });
    }
}
