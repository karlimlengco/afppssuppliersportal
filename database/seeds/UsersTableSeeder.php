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
        $password2 = Hash::make("12345");

        \App\User::create([
            "uuid"          => $faker->unique()->uuid,
            "email"         => 'kblimlengco@yahoo.com',
            "password"      => $password,
            "username"      => 'kblimlengco',
            "first_name"    => 'Karl',
            "surname"       => 'Limlengco',
            "middle_name"   => 'S',
            "gender"        => 'male',
            "address"       => 'Makati City',
            "contact_number"=> '09363494225',
            "designation"   => 'Administrator',
            "permissions"   => ['superuser'=>'1'],
            "status"        => '1',
        ]);

        \App\User::create([
            "uuid"          => $faker->unique()->uuid,
            "email"         => 'karlimlengco@gmail.com',
            "password"      => $password,
            "username"      => 'testuser',
            "first_name"    => 'Pablito',
            "surname"       => 'Escobar',
            "middle_name"   => 'K',
            "gender"        => 'male',
            "address"       => 'Columbia City',
            "status"        => '1',
            "designation"   => 'Tester',
            "contact_number"=> '09363494228'
        ]);

        \App\User::create([
            "uuid"          => $faker->unique()->uuid,
            "email"         => 'thirdtauro@yahoo.com',
            "password"      => $password2,
            "username"      => 'thirdtauro',
            "first_name"    => 'Romeo',
            "surname"       => 'Tauro',
            "middle_name"   => 'Cocjin',
            "gender"        => 'male',
            "address"       => 'ACS for CEIS, OPS6, AFPPS CGEA, QC',
            "status"        => '1',
            "designation"   => 'Network Admin',
            "contact_number"=> '09162630925'
        ]);

        \App\User::create([
            "uuid"          => $faker->unique()->uuid,
            "email"         => 'commander@afpps.ph',
            "password"      => $password2,
            "username"      => 'C,AFPPS',
            "first_name"    => 'Ernesto',
            "surname"       => 'T',
            "middle_name"   => 'Lopena',
            "gender"        => 'male',
            "address"       => 'Camp Aguinaldo',
            "status"        => '1',
            "designation"   => 'Commander, AFPPS',
            "contact_number"=> '5738492'
        ]);

        \App\User::create([
            "uuid"          => $faker->unique()->uuid,
            "email"         => 'spade133876@yahoo.com',
            "password"      => $password2,
            "username"      => 'CHIEF, OPS6',
            "first_name"    => 'FRANCISCO BUTCH',
            "surname"       => 'B',
            "middle_name"   => 'CANZADO',
            "gender"        => 'male',
            "address"       => 'HAFP Procurement Service, Camp General
Emilio Aguinaldo, Quezon City',
            "status"        => '1',
            "designation"   => 'Chief OPS6',
            "contact_number"=> '09399395771'
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

        \App\Activation::create([
            "user_id"         => 3,
            "code"         => 3,
            "completed"         => 1,
        ]);

        \App\Activation::create([
            "user_id"         => 4,
            "code"         => 4,
            "completed"         => 1,
        ]);

        \App\Activation::create([
            "user_id"         => 5,
            "code"         => 5,
            "completed"         => 1,
        ]);
    }
}
