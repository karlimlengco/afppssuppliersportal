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

        $faker = \Faker\Factory::create();
        $procurements = collect([
            new ProcurementCenterEloquent(['id' => $faker->unique()->uuid, "name"=> 'PA Procurement Center', "short_code"=> 'PA PC', 'address' => "Fort Andress Bonifacio, Metro Manila", 'programs' => '1']),
            new ProcurementCenterEloquent(['id' => $faker->unique()->uuid, "name"=> 'PAF Procurement Center', "short_code"=> 'PAF PC', 'address' => "Colonel Jesus Villamor Air Base, Pasay City, Metro Manila", 'programs' => '2']),
            new ProcurementCenterEloquent(['id' => $faker->unique()->uuid, "name"=> 'PN Procurement Center', "short_code"=> 'PN PC', 'address' => "Naval Station Jose Francisco (BNS), Taguig City, Metro Manila", 'programs' => '3']),
            new ProcurementCenterEloquent(['id' => $faker->unique()->uuid, "name"=> 'GHQ Procurement Center', "short_code"=> 'GHQ PC', 'address' => "Camp General Aguinaldo, QC", 'programs' => '4']),
            new ProcurementCenterEloquent(['id' => $faker->unique()->uuid, "name"=> '101ST Contracting Office', "short_code"=> '101ST CO', 'address' => "Fort Andress Bonifacio, Metro Manila", 'programs' => '1']),
            new ProcurementCenterEloquent(['id' => $faker->unique()->uuid, "name"=> '102ND Contracting Office', "short_code"=> '102ND CO', 'address' => "Fort Andress Bonifacio, Metro Manila", 'programs' => '1']),
            new ProcurementCenterEloquent(['id' => $faker->unique()->uuid, "name"=> '103RD Contracting Office', "short_code"=> '103RD CO', 'address' => "Fort Andress Bonifacio, Metro Manila", 'programs' => '1']),
            new ProcurementCenterEloquent(['id' => $faker->unique()->uuid, "name"=> '104TH Contracting Office', "short_code"=> '104TH CO', 'address' => "Fort Andress Bonifacio, Metro Manila", 'programs' => '1']),
            new ProcurementCenterEloquent(['id' => $faker->unique()->uuid, "name"=> '105TH Contracting Office', "short_code"=> '105TH CO', 'address' => "Fort Andress Bonifacio, Metro Manila", 'programs' => '1']),
            new ProcurementCenterEloquent(['id' => $faker->unique()->uuid, "name"=> '106TH Contracting Office', "short_code"=> '106TH CO', 'address' => "Fort Andress Bonifacio, Metro Manila", 'programs' => '1']),
            new ProcurementCenterEloquent(['id' => $faker->unique()->uuid, "name"=> '107TH Contracting Office', "short_code"=> '107TH CO', 'address' => "Fort Andress Bonifacio, Metro Manila", 'programs' => '1']),
            new ProcurementCenterEloquent(['id' => $faker->unique()->uuid, "name"=> '108TH Contracting Office', "short_code"=> '108TH CO', 'address' => "Fort Andress Bonifacio, Metro Manila", 'programs' => '1']),
            new ProcurementCenterEloquent(['id' => $faker->unique()->uuid, "name"=> '109TH Contracting Office', "short_code"=> '109TH CO', 'address' => "Fort Andress Bonifacio, Metro Manila", 'programs' => '1']),
            new ProcurementCenterEloquent(['id' => $faker->unique()->uuid, "name"=> '110TH Contracting Office', "short_code"=> '110TH CO', 'address' => "Fort Andress Bonifacio, Metro Manila", 'programs' => '1']),
            new ProcurementCenterEloquent(['id' => $faker->unique()->uuid, "name"=> '111TH Contracting Office', "short_code"=> '111TH CO', 'address' => "Fort Andress Bonifacio, Metro Manila", 'programs' => '1']),
            new ProcurementCenterEloquent(['id' => $faker->unique()->uuid, "name"=> '112TH Contracting Office', "short_code"=> '112TH CO', 'address' => "Fort Andress Bonifacio, Metro Manila", 'programs' => '1']),
            new ProcurementCenterEloquent(['id' => $faker->unique()->uuid, "name"=> '201ST Contracting Office', "short_code"=> '201ST CO', 'address' => "Colonel Jesus Villamor Air Base, Pasay City, Metro Manila", 'programs' => '2']),
            new ProcurementCenterEloquent(['id' => $faker->unique()->uuid, "name"=> '202ND Contracting Office', "short_code"=> '202ND CO', 'address' => "Colonel Jesus Villamor Air Base, Pasay City, Metro Manila", 'programs' => '2']),
            new ProcurementCenterEloquent(['id' => $faker->unique()->uuid, "name"=> '301ST Contracting Office', "short_code"=> '301ST CO', 'address' => "Naval Station Jose Francisco (BNS), Taguig City, Metro Manila", 'programs' => '3']),
            new ProcurementCenterEloquent(['id' => $faker->unique()->uuid, "name"=> '302ND Contracting Office', "short_code"=> '302ND CO', 'address' => "Naval Station Jose Francisco (BNS), Taguig City, Metro Manila", 'programs' => '3']),
            new ProcurementCenterEloquent(['id' => $faker->unique()->uuid, "name"=> '303RD Contracting Office', "short_code"=> '303RD CO', 'address' => "Naval Station Jose Francisco (BNS), Taguig City, Metro Manila", 'programs' => '3']),
            new ProcurementCenterEloquent(['id' => $faker->unique()->uuid, "name"=> '304TH Contracting Office', "short_code"=> '304TH CO', 'address' => "Naval Station Jose Francisco (BNS), Taguig City, Metro Manila", 'programs' => '3']),
            new ProcurementCenterEloquent(['id' => $faker->unique()->uuid, "name"=> '305TH Contracting Office', "short_code"=> '305TH CO', 'address' => "Naval Station Jose Francisco (BNS), Taguig City, Metro Manila", 'programs' => '3']),
            new ProcurementCenterEloquent(['id' => $faker->unique()->uuid, "name"=> '306TH Contracting Office', "short_code"=> '306TH CO', 'address' => "Naval Station Jose Francisco (BNS), Taguig City, Metro Manila", 'programs' => '3']),
            new ProcurementCenterEloquent(['id' => $faker->unique()->uuid, "name"=> '401ST Contracting Office', "short_code"=> '401ST CO', 'address' => "Camp General Aguinaldo, QC", 'programs' => '4']),
            new ProcurementCenterEloquent(['id' => $faker->unique()->uuid, "name"=> '402ND Contracting Office', "short_code"=> '402ND CO', 'address' => "Camp General Aguinaldo, QC", 'programs' => '4']),
            new ProcurementCenterEloquent(['id' => $faker->unique()->uuid, "name"=> '403RD CO ', "short_code"=> '403RD CO ', 'address' => "Camp General Aguinaldo, QC", 'programs' => '4']),
            new ProcurementCenterEloquent(['id' => $faker->unique()->uuid, "name"=> '404TH Contracting Office', "short_code"=> '404TH CO', 'address' => "Camp General Aguinaldo, QC", 'programs' => '4']),
            new ProcurementCenterEloquent(['id' => $faker->unique()->uuid, "name"=> '405TH Contracting Office', "short_code"=> '405TH CO', 'address' => "Camp General Aguinaldo, QC", 'programs' => '4']),
            new ProcurementCenterEloquent(['id' => $faker->unique()->uuid, "name"=> '406TH Contracting Office', "short_code"=> '406TH CO', 'address' => "Camp General Aguinaldo, QC", 'programs' => '4']),
            new ProcurementCenterEloquent(['id' => $faker->unique()->uuid, "name"=> '407TH Contracting Office', "short_code"=> '407TH CO', 'address' => "Camp General Aguinaldo, QC", 'programs' => '4']),
            new ProcurementCenterEloquent(['id' => $faker->unique()->uuid, "name"=> '408TH Contracting Office', "short_code"=> '408TH CO', 'address' => "Camp General Aguinaldo, QC", 'programs' => '4']),
            new ProcurementCenterEloquent(['id' => $faker->unique()->uuid, "name"=> 'HEADQUARTERS AFPPS', "short_code"=> 'HAFPPS', 'address' => "Camp General Emilio Aguinaldo, Quezon City", 'programs' => '1'])
        ]);

        $procurements->each(function($procurement) {
            $procurement->save();
        });
    }
}
