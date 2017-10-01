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


        $faker = \Faker\Factory::create();
        $datas = collect([
            new SignatoryEloquent(['id' => $faker->unique()->uuid, "name" => 'A C Q MENDOZA', "designation" => 'UNIT HEAD', 'ranks' => 'BGEN', 'branch' => 'AFP']),
            new SignatoryEloquent(['id' => $faker->unique()->uuid, "name" => 'ABDULMANAN A ABAS', "designation" => 'CHAIRMAN', 'ranks' => 'MAJ', 'branch' => '(INF) AFP']),
            new SignatoryEloquent(['id' => $faker->unique()->uuid, "name" => 'ABRAHAM CLARO C CASIS ', "designation" => 'MFO', 'ranks' => 'COL', 'branch' => '(MNSA) PA']),
            new SignatoryEloquent(['id' => $faker->unique()->uuid, "name" => 'ADOLFO B ALBALATE', "designation" => 'LEGAL', 'ranks' => 'COL', 'branch' => '(GSC) PN']),
            new SignatoryEloquent(['id' => $faker->unique()->uuid, "name" => 'COL ADRIANO T CASTRO', "designation" => 'Sec Gen', 'ranks' => 'COL', 'branch' => 'PA']),
            new SignatoryEloquent(['id' => $faker->unique()->uuid, "name" => 'ALANO S ABDULHALIM', "designation" => 'NOA', 'ranks' => 'LTCOL', 'branch' => 'PN']),
            new SignatoryEloquent(['id' => $faker->unique()->uuid, "name" => 'ADONIS ARIEL G ORIO', "designation" => 'CHAIRMAN, CCC', 'ranks' => 'LTC', 'branch' => '(GSC)']),
            new SignatoryEloquent(['id' => $faker->unique()->uuid, "name" => 'ROMULO R SATPARAM', "designation" => 'COMMANDING OFFICER', 'ranks' => 'LTC', 'branch' => '(OS) PA']),
            new SignatoryEloquent(['id' => $faker->unique()->uuid, "name" => 'THIRD TAURO', "designation" => 'CHIEF', 'ranks' => 'LTC', 'branch' => 'PA']),
        ]);

        $datas->each(function($data) {
            $data->save();
        });
    }
}
