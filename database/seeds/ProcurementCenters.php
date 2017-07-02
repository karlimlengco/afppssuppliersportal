<?php

use Illuminate\Database\Seeder;

class ProcurementCenters extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Revlv\Settings\ProcurementCenters\ProcurementCenterEloquent::insert([
            [
                "name"          => 'GHQ PC',
                "address"       => 'Camp General Aguinaldo, QC',
                'programs'      => '4',
            ],
            [
                "name"          => 'PA PC',
                "address"       => 'Fort Andress Bonifacio, Metro Manila',
                'programs'      => '1'

            ],
            [
                "name"          => 'PN PC',
                "address"       => 'Naval Station Jose Francisco (BNS), Taguig City, Metro Manila',
                'programs'      => '3'
            ],
            [
                "name"          => 'PAF PC',
                "address"       => 'Colonel Jesus Villamor Air Base, Pasay City, Metro Manila',
                'programs'      => '2'
            ],
            [
                "name"          => '101st CO',
                "address"       => 'Camp Sangan, Pulacan, Zambo Sur',
                'programs'      => '1'
            ],
            [
                "name"          => '102nd CO',
                "address"       => 'Camp Capinpin, Tanay Rizal',
                'programs'      => '1'
            ],
            [
                "name"          => '201st CO',
                "address"       => 'Clark Air Base, Pampanga',
                'programs'      => '2'
            ],
            [
                "name"          => '202nd CO',
                "address"       => 'Fernando Air Base, Lipa City Batangas',
                'programs'      => '2'
            ],
            [
                "name"          => '301st CO',
                "address"       => 'Naval Station Ernesto Ogbinar, Poro Point, San Fernandom La Union',
                'programs'      => '3'
            ],
            [
                "name"          => '302nd CO',
                "address"       => 'Naval Station Pascual Ledesma, Fort San Felipe, Cavite City',
                'programs'      => '3'
            ],
            [
                "name"          => '401st CO',
                "address"       => 'NOLCOM, Camp Aquino, Tarlac City, Tarlac',
                'programs'      => '4',
            ],
            [
                "name"          => '402nd CO',
                "address"       => 'SOLCOM, Lucena City, Quezon',
                'programs'      => '4',
            ]
        ]);
    }
}
