<?php

use Illuminate\Database\Seeder;
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
        $datas = collect([
            new PermissionEloquent(["permission" => 'settings.*', "description" => 'Settings']),
            new PermissionEloquent(["permission" => 'reports.*', "description" => 'Reports']),
            new PermissionEloquent(["permission" => 'maintenance.*', "description" => 'Maintenance']),
            new PermissionEloquent(["permission" => 'procurements.*', "description" => 'Alternatice Mode of Procurement']),
            new PermissionEloquent(["permission" => 'biddings.*', "description" => 'Competitive Bidding']),
            new PermissionEloquent(["permission" => 'library.*', "description" => 'Library'])
        ]);

        $datas->each(function($data) {
            $data->save();
        });
    }
}
