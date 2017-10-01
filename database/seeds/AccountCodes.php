<?php

use Illuminate\Database\Seeder;
use League\Csv\Reader;

class AccountCodes extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        $reader = Reader::createFromPath(database_path().'/csv/account_codes.csv');
        $reader->each(function($row, $rowOffset) use($faker) {
            \Revlv\Settings\AccountCodes\AccountCodeEloquent::create([
                'id' => $faker->unique()->uuid,
                'old_account_code' => $row[0],
                'new_account_code' => $row[1],
                'name' => $row[2],
                'main_class' => $row[3],
                'sub_class' => $row[4],
                'account_group' => $row[5],
                'detailed_account' => $row[6],
                'contra_account' => $row[7],
                'sub_account' => $row[8],
                'expense_class_id' => ($row[0] >= 701 && $row[0] <= 749) ? 1 : null
            ]);
            return true;
        });
    }
}
