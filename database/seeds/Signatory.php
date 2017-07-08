<?php

use Illuminate\Database\Seeder;
use \Revlv\Settings\Signatories\SignatoryEloquent;

class Signatory extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        $datas = collect([
            new SignatoryEloquent(["name" => 'A C Q MENDOZA', "designation" => 'UNIT HEAD', 'ranks' => 'BGEN AFP']),
            new SignatoryEloquent(["name" => 'ABDULMANAN A ABAS', "designation" => 'CHAIRMAN', 'ranks' => 'MAJ (INF) AFP']),
            new SignatoryEloquent(["name" => 'ABRAHAM CLARO C CASIS ', "designation" => 'MFO', 'ranks' => 'COL (MNSA) PA']),
            new SignatoryEloquent(["name" => 'ADOLFO B ALBALATE', "designation" => 'LEGAL', 'ranks' => 'COL (GSC) PN']),
            new SignatoryEloquent(["name" => 'COL ADRIANO T CASTRO', "designation" => 'Sec Gen', 'ranks' => 'COL PA']),
            new SignatoryEloquent(["name" => 'ALANO S ABDULHALIM', "designation" => 'NOA', 'ranks' => 'LTCOL PN']),
            new SignatoryEloquent(["name" => 'ADONIS ARIEL G ORIO', "designation" => 'CHAIRMAN, CCC', 'ranks' => 'LTC (GSC)']),
            new SignatoryEloquent(["name" => 'ROMULO R SATPARAM', "designation" => 'COMMANDING OFFICER', 'ranks' => 'LTC (OS) PA']),
            new SignatoryEloquent(["name" => 'THIRD TAURO', "designation" => 'CHIEF', 'ranks' => 'LTC PA']),
        ]);

        $datas->each(function($data) {
            $data->save();
        });
    }
}
