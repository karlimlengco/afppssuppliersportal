<?php

use Illuminate\Database\Seeder;

use Faker\Factory as Faker;

class UsersTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $faker = Faker::create();

        $password = Hash::make("password");

        \App\User::create([
            "email"         => 'kblimlengco@yahoo.com',
            "password"      => $password,
            "username"      => 'kblimlengco',
            "first_name"    => 'Karl',
            "surname"       => 'Limlengco',
            "middle_name"   => 'S',
            "gender"        => 'male',
            "address"       => 'Makati City',
            "contact_number"=> '09363494225',
            "permissions"   => ['superuser'=>'1'],
            "status"        => '1',
        ]);

        \App\User::create([
            "email"         => 'karlimlengco@gmail.com',
            "password"      => $password,
            "username"      => 'testuser',
            "first_name"    => 'Pablito',
            "surname"       => 'Escobar',
            "middle_name"   => 'K',
            "gender"        => 'male',
            "address"       => 'Columbia City',
            "status"        => '1',
            "contact_number"=> '09363494228'
        ]);


        \App\Activation::create([
            "user_id"         => 1,
            "code"         => 1,
            "completed"         => 1,
        ]);

        \App\Activation::create([
            "user_id"         => 2,
            "code"         => 2,
            "completed"         => 1,
        ]);
    }
}
