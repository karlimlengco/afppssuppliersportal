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
                "name"              => 'A C Q MENDOZA',
                "designation"       => 'UNIT HEAD',
                'ranks'             => 'BGEN AFP'
            ],
            [
                "name"              => 'ABDULMANAN A ABAS',
                "designation"       => 'CHAIRMAN',
                'ranks'             => 'MAJ (INF) AFP'
            ],
            [
                "name"              => 'ABRAHAM CLARO C CASIS ',
                "designation"       => 'MFO',
                'ranks'             => 'COL (MNSA) PA'
            ],
            [
                "name"              => 'ADOLFO B ALBALATE',
                "designation"       => 'LEGAL',
                'ranks'             => 'COL (GSC) PN'
            ],
            [
                "name"              => 'COL ADRIANO T CASTRO',
                "designation"       => 'Sec Gen',
                'ranks'             => 'COL PA'
            ],
            [
                "name"              => 'ALANO S ABDULHALIM',
                "designation"       => 'NOA',
                'ranks'             => 'LTCOL PN'
            ],
            [
                "name"              => 'ADONIS ARIEL G ORIO',
                "designation"       => 'CHAIRMAN, CCC',
                'ranks'             => 'LTC (GSC)'
            ],
            [
                "name"              => 'ROMULO R SATPARAM',
                "designation"       => 'COMMANDING OFFICER',
                'ranks'             => 'LTC (OS) PA'
            ],
            [
                "name"              => 'THIRD TAURO',
                "designation"       => 'CHIEF',
                'ranks'             => 'LTC PA'
            ]
        ]);
    }
}
