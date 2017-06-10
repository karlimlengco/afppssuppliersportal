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
                "address"       => 'Camp General Aguinaldo, QC'
            ],
            [
                "name"          => 'PA PC',
                "address"       => 'Fort Andress Bonifacio, Metro Manila'
            ],
            [
                "name"          => 'PN PC',
                "address"       => 'Naval Station Jose Francisco (BNS), Taguig City, Metro Manila'
            ],
            [
                "name"          => 'PAF PC',
                "address"       => 'Colonel Jesus Villamor Air Base, Pasay City, Metro Manila'
            ]
        ]);
    }
}
