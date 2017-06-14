<?php

use Illuminate\Database\Seeder;

class Signatory extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Revlv\Settings\Signatories\SignatoryEloquent::insert([
            [
                "name"              => 'BGEN A C Q MENDOZA AFP',
                "designation"       => 'UNIT HEAD'
            ],
            [
                "name"              => 'MAJ ABDULMANAN A ABAS (INF)',
                "designation"       => 'CHAIRMAN'
            ],
            [
                "name"              => 'COL ABRAHAM CLARO C CASIS (MNSA) PA',
                "designation"       => 'MFO'
            ],
            [
                "name"              => 'COL ADOLFO B ALBALATE (GSC) PN',
                "designation"       => 'LEGAL'
            ],
            [
                "name"              => 'COL ADRIANO T CASTRO PA',
                "designation"       => 'Sec Gen'
            ],
            [
                "name"              => 'LTCOL ALANO S ABDULHALIM PN',
                "designation"       => 'NOA'
            ],
            [
                "name"              => 'LTC ADONIS ARIEL G ORIO (GSC) PA',
                "designation"       => 'CHAIRMAN, CCC'
            ],
            [
                "name"              => 'LTC ROMULO R SATPARAM  (OS) PA',
                "designation"       => 'COMMANDING OFFICER'
            ],
            [
                "name"              => 'LTC THIRD TAURO PA',
                "designation"       => 'CHIEF'
            ]
        ]);
    }
}
