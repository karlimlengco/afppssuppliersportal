<?php

use Illuminate\Database\Seeder;
use \Revlv\Settings\ProcurementCenters\ProcurementCenterEloquent;

class ProcurementCenters extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $procurements = collect([
            new ProcurementCenterEloquent(["name"=> 'PA PC', 'address' => "Fort Andress Bonifacio, Metro Manila", 'programs' => '1']),
            new ProcurementCenterEloquent(["name"=> 'PAF PC', 'address' => "Colonel Jesus Villamor Air Base, Pasay City, Metro Manila", 'programs' => '2']),
            new ProcurementCenterEloquent(["name"=> 'PN PC', 'address' => "Naval Station Jose Francisco (BNS), Taguig City, Metro Manila", 'programs' => '3']),
            new ProcurementCenterEloquent(["name"=> 'GHQ PC', 'address' => "Camp General Aguinaldo, QC", 'programs' => '4']),
            new ProcurementCenterEloquent(["name"=> '101ST CO', 'address' => "Fort Andress Bonifacio, Metro Manila", 'programs' => '1']),
            new ProcurementCenterEloquent(["name"=> '102ND CO', 'address' => "Fort Andress Bonifacio, Metro Manila", 'programs' => '1']),
            new ProcurementCenterEloquent(["name"=> '103RD CO', 'address' => "Fort Andress Bonifacio, Metro Manila", 'programs' => '1']),
            new ProcurementCenterEloquent(["name"=> '104TH CO', 'address' => "Fort Andress Bonifacio, Metro Manila", 'programs' => '1']),
            new ProcurementCenterEloquent(["name"=> '105TH CO', 'address' => "Fort Andress Bonifacio, Metro Manila", 'programs' => '1']),
            new ProcurementCenterEloquent(["name"=> '106TH CO', 'address' => "Fort Andress Bonifacio, Metro Manila", 'programs' => '1']),
            new ProcurementCenterEloquent(["name"=> '107TH CO', 'address' => "Fort Andress Bonifacio, Metro Manila", 'programs' => '1']),
            new ProcurementCenterEloquent(["name"=> '108TH CO', 'address' => "Fort Andress Bonifacio, Metro Manila", 'programs' => '1']),
            new ProcurementCenterEloquent(["name"=> '109TH CO', 'address' => "Fort Andress Bonifacio, Metro Manila", 'programs' => '1']),
            new ProcurementCenterEloquent(["name"=> '110TH CO', 'address' => "Fort Andress Bonifacio, Metro Manila", 'programs' => '1']),
            new ProcurementCenterEloquent(["name"=> '111TH CO', 'address' => "Fort Andress Bonifacio, Metro Manila", 'programs' => '1']),
            new ProcurementCenterEloquent(["name"=> '112TH CO', 'address' => "Fort Andress Bonifacio, Metro Manila", 'programs' => '1']),
            new ProcurementCenterEloquent(["name"=> '201ST CO', 'address' => "Colonel Jesus Villamor Air Base, Pasay City, Metro Manila", 'programs' => '2']),
            new ProcurementCenterEloquent(["name"=> '202ND CO', 'address' => "Colonel Jesus Villamor Air Base, Pasay City, Metro Manila", 'programs' => '2']),
            new ProcurementCenterEloquent(["name"=> '301ST CO', 'address' => "Naval Station Jose Francisco (BNS), Taguig City, Metro Manila", 'programs' => '3']),
            new ProcurementCenterEloquent(["name"=> '302ND CO', 'address' => "Naval Station Jose Francisco (BNS), Taguig City, Metro Manila", 'programs' => '3']),
            new ProcurementCenterEloquent(["name"=> '303RD CO', 'address' => "Naval Station Jose Francisco (BNS), Taguig City, Metro Manila", 'programs' => '3']),
            new ProcurementCenterEloquent(["name"=> '304TH CO', 'address' => "Naval Station Jose Francisco (BNS), Taguig City, Metro Manila", 'programs' => '3']),
            new ProcurementCenterEloquent(["name"=> '305TH CO', 'address' => "Naval Station Jose Francisco (BNS), Taguig City, Metro Manila", 'programs' => '3']),
            new ProcurementCenterEloquent(["name"=> '306TH CO', 'address' => "Naval Station Jose Francisco (BNS), Taguig City, Metro Manila", 'programs' => '3']),
            new ProcurementCenterEloquent(["name"=> '401ST CO', 'address' => "Camp General Aguinaldo, QC", 'programs' => '4']),
            new ProcurementCenterEloquent(["name"=> '402ND CO', 'address' => "Camp General Aguinaldo, QC", 'programs' => '4']),
            new ProcurementCenterEloquent(["name"=> '403RD CO ', 'address' => "Camp General Aguinaldo, QC", 'programs' => '4']),
            new ProcurementCenterEloquent(["name"=> '404TH CO', 'address' => "Camp General Aguinaldo, QC", 'programs' => '4']),
            new ProcurementCenterEloquent(["name"=> '405TH CO', 'address' => "Camp General Aguinaldo, QC", 'programs' => '4']),
            new ProcurementCenterEloquent(["name"=> '406TH CO', 'address' => "Camp General Aguinaldo, QC", 'programs' => '4']),
            new ProcurementCenterEloquent(["name"=> '407TH CO', 'address' => "Camp General Aguinaldo, QC", 'programs' => '4']),
            new ProcurementCenterEloquent(["name"=> '408TH CO', 'address' => "Camp General Aguinaldo, QC", 'programs' => '4'])
        ]);

        $procurements->each(function($procurement) {
            $procurement->save();
        });
    }
}
