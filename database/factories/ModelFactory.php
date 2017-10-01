<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestEloquent::class, function (Faker\Generator $faker) {
    return [
        'project_name'      => $faker->name,
        'prepared_by'       => 1,
        'total_amount'      => 1,
        'upr_number'        => $faker->unique()->numerify('UPR ###'),
        'ref_number'        => $faker->unique()->numerify('UPR ###'),
        'place_of_delivery' => $faker->city,
        'procurement_office'=> $faker->randomElement(\Revlv\Settings\ProcurementCenters\ProcurementCenterEloquent::pluck('id')->toArray() ),
        // 'mode_of_procurement'=> 'public_bidding',
        'mode_of_procurement'=> $faker->randomElement(\Revlv\Settings\ModeOfProcurements\ModeOfProcurementEloquent::pluck('id')->toArray() ),
        'procurement_type'=> $faker->randomElement(\Revlv\Settings\ProcurementTypes\ProcurementTypeEloquent::pluck('id')->toArray() ),
        'chargeability'=> $faker->randomElement(\Revlv\Settings\Chargeability\ChargeabilityEloquent::pluck('id')->toArray() ),
        'units'=> $faker->randomElement(\Revlv\Settings\CateredUnits\CateredUnitEloquent::pluck('id')->toArray() ),
        'purpose' => $faker->sentence(4),
        'new_account_code'=> $faker->randomElement(\Revlv\Settings\AccountCodes\AccountCodeEloquent::pluck('id')->toArray() ),
        'date_prepared'=> $faker->date('Y-m-d', 'now') ,
    ];
});
