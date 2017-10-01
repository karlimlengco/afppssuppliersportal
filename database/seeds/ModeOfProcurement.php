<?php

use Illuminate\Database\Seeder;
use \Revlv\Settings\ModeOfProcurements\ModeOfProcurementEloquent;

class ModeOfProcurement extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $faker = \Faker\Factory::create();
        $modes = collect([
            new ModeOfProcurementEloquent([
                'id' => $faker->unique()->uuid, "name" => 'Limited Source Bidding', "description"   => 'Limited Source Bidding']),
            new ModeOfProcurementEloquent([
                'id' => $faker->unique()->uuid, "name" => 'Direct Contracting', "description"   => 'Direct Contracting']),
            new ModeOfProcurementEloquent([
                'id' => $faker->unique()->uuid, "name" => 'Repeat Order', "description"   => 'Repeat Order']),
            new ModeOfProcurementEloquent([
                'id' => $faker->unique()->uuid, "name" => 'Shopping 52.1(a)', "description"   => 'Shopping 52.1(a)']),
            new ModeOfProcurementEloquent([
                'id' => $faker->unique()->uuid, "name" => 'Shopping 52.1(b)', "description"   => 'Shopping 52.1(b)']),
            new ModeOfProcurementEloquent([
                'id' => $faker->unique()->uuid, "name" => 'Negotiated 53.1', "description"   => 'Negotiated 53.1']),
            new ModeOfProcurementEloquent([
                'id' => $faker->unique()->uuid, "name" => 'Negotiated 53.2', "description"   => 'Negotiated 53.2']),
            new ModeOfProcurementEloquent([
                'id' => $faker->unique()->uuid, "name" => 'Negotiated 53.5', "description"   => 'Negotiated 53.5']),
            new ModeOfProcurementEloquent([
                'id' => $faker->unique()->uuid, "name" => 'Negotiated 53.7', "description"   => 'Negotiated 53.7']),
            new ModeOfProcurementEloquent([
                'id' => $faker->unique()->uuid, "name" => 'Negotiated 53.9', "description"   => 'Negotiated 53.9'])
        ]);

        $modes->each(function($mode) {
            $mode->save();
        });
    }

}
