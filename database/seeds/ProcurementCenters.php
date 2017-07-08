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
            new ProcurementCenterEloquent(["name"=> 'GHQ PROCUREMENT CENTER', 'address' => "Camp General Aguinaldo, QC", 'programs' => '4']),
            new ProcurementCenterEloquent(["name"=> '101ST CONTRACTING OFFICE', 'address' => "Fort Andress Bonifacio, Metro Manila", 'programs' => '1']),
            new ProcurementCenterEloquent(["name"=> '102ND CONTRACTING OFFICE', 'address' => "Fort Andress Bonifacio, Metro Manila", 'programs' => '1']),
            new ProcurementCenterEloquent(["name"=> '103RD CONTRACTING OFFICE', 'address' => "Fort Andress Bonifacio, Metro Manila", 'programs' => '1']),
            new ProcurementCenterEloquent(["name"=> '104TH CONTRACTING OFFICE', 'address' => "Fort Andress Bonifacio, Metro Manila", 'programs' => '1']),
            new ProcurementCenterEloquent(["name"=> '105TH CONTRACTING OFFICE', 'address' => "Fort Andress Bonifacio, Metro Manila", 'programs' => '1']),
            new ProcurementCenterEloquent(["name"=> '106TH CONTRACTING OFFICE', 'address' => "Fort Andress Bonifacio, Metro Manila", 'programs' => '1']),
            new ProcurementCenterEloquent(["name"=> '107TH CONTRACTING OFFICE', 'address' => "Fort Andress Bonifacio, Metro Manila", 'programs' => '1']),
            new ProcurementCenterEloquent(["name"=> '108TH CONTRACTING OFFICE', 'address' => "Fort Andress Bonifacio, Metro Manila", 'programs' => '1']),
            new ProcurementCenterEloquent(["name"=> '109TH CONTRACTING OFFICE', 'address' => "Fort Andress Bonifacio, Metro Manila", 'programs' => '1']),
            new ProcurementCenterEloquent(["name"=> '110TH CONTRACTING OFFICE', 'address' => "Fort Andress Bonifacio, Metro Manila", 'programs' => '1']),
            new ProcurementCenterEloquent(["name"=> '111TH CONTRACTING OFFICE', 'address' => "Fort Andress Bonifacio, Metro Manila", 'programs' => '1']),
            new ProcurementCenterEloquent(["name"=> '112TH CONTRACTING OFFICE', 'address' => "Fort Andress Bonifacio, Metro Manila", 'programs' => '1']),
            new ProcurementCenterEloquent(["name"=> '201ST CONTRACTING OFFICE', 'address' => "Colonel Jesus Villamor Air Base, Pasay City, Metro Manila", 'programs' => '2']),
            new ProcurementCenterEloquent(["name"=> '202ND CONTRACTING OFFICE', 'address' => "Colonel Jesus Villamor Air Base, Pasay City, Metro Manila", 'programs' => '2']),
            new ProcurementCenterEloquent(["name"=> '301ST CONTRACTING OFFICE', 'address' => "Naval Station Jose Francisco (BNS), Taguig City, Metro Manila", 'programs' => '3']),
            new ProcurementCenterEloquent(["name"=> '302ND CONTRACTING OFFICE', 'address' => "Naval Station Jose Francisco (BNS), Taguig City, Metro Manila", 'programs' => '3']),
            new ProcurementCenterEloquent(["name"=> '303RD CONTRACTING OFFICE', 'address' => "Naval Station Jose Francisco (BNS), Taguig City, Metro Manila", 'programs' => '3']),
            new ProcurementCenterEloquent(["name"=> '304TH CONTRACTING OFFICE', 'address' => "Naval Station Jose Francisco (BNS), Taguig City, Metro Manila", 'programs' => '3']),
            new ProcurementCenterEloquent(["name"=> '305TH CONTRACTING OFFICE', 'address' => "Naval Station Jose Francisco (BNS), Taguig City, Metro Manila", 'programs' => '3']),
            new ProcurementCenterEloquent(["name"=> '306TH CONTRACTING OFFICE', 'address' => "Naval Station Jose Francisco (BNS), Taguig City, Metro Manila", 'programs' => '3']),
            new ProcurementCenterEloquent(["name"=> '401ST CONTRACTING OFFICE', 'address' => "Camp General Aguinaldo, QC", 'programs' => '4']),
            new ProcurementCenterEloquent(["name"=> '402ND CONTRACTING OFFICE', 'address' => "Camp General Aguinaldo, QC", 'programs' => '4']),
            new ProcurementCenterEloquent(["name"=> '403RD CONTRACTING OFFICE ', 'address' => "Camp General Aguinaldo, QC", 'programs' => '4']),
            new ProcurementCenterEloquent(["name"=> '404TH CONTRACTING OFFICE', 'address' => "Camp General Aguinaldo, QC", 'programs' => '4']),
            new ProcurementCenterEloquent(["name"=> '405TH CONTRACTING OFFICE', 'address' => "Camp General Aguinaldo, QC", 'programs' => '4']),
            new ProcurementCenterEloquent(["name"=> '406TH CONTRACTING OFFICE', 'address' => "Camp General Aguinaldo, QC", 'programs' => '4']),
            new ProcurementCenterEloquent(["name"=> '407TH CONTRACTING OFFICE', 'address' => "Camp General Aguinaldo, QC", 'programs' => '4']),
            new ProcurementCenterEloquent(["name"=> '408TH CONTRACTING OFFICE', 'address' => "Camp General Aguinaldo, QC", 'programs' => '4'])
        ]);

        $procurements->each(function($procurement) {
            $procurement->save();
        });
    }
}
