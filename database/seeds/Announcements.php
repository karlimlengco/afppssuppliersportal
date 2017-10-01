<?php

use Illuminate\Database\Seeder;
use \Revlv\Settings\Announcements\AnnouncementEloquent;
use Faker\Factory as Faker;

class Announcements extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $datas = collect([
            new AnnouncementEloquent(["id" => $faker->unique()->uuid, "title" => 'Test', "message"   => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Fugit debitis quod enim ullam deleniti beatae fugiat rem corporis officia ea obcaecati distinctio libero, praesentium illum error delectus sequi. Blanditiis, quis.', 'post_at' => '2017-07-03', 'status' => 1]),
        ]);

        $datas->each(function($data) {
            $data->save();
        });
    }
}
