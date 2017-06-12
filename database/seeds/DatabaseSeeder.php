<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(ModeOfProcurement::class);
        $this->call(ProcurementCenters::class);
        $this->call(AccountCodes::class);
        $this->call(PaymentTerm::class);
        $this->call(Units::class);
    }
}
