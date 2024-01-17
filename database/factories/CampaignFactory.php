<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Campaign as Model;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;

$factory->define(Model::class, function (Faker $faker) {
    $areaId = DB::table('areas')->select('id')->inRandomOrder()->first()->id;
    $producerId = DB::table('producers')->select('id')->inRandomOrder()->first()->id;
    $videoId = DB::table('videos')->select('id')->inRandomOrder()->first()->id;
    $accountingId = DB::table('accountings')->select('id')->inRandomOrder()->first()->id;
    return [
        'area_id' => $areaId,
        'producer_id' => $producerId,
        'video_id' => $videoId,
        'accounting_id' => $accountingId,
        'title' => $faker->title,
        'description' => $faker->text,
        'geolocation' => $faker->streetAddress,
        'min_age' => $faker->numberBetween(18, 30),
        'max_age' => $faker->numberBetween(31, 60),
        'gender' => $faker->numberBetween(0, 2),
        'budget' => $faker->randomFloat(5, 100, 1000),
        'reward' => $faker->randomFloat(5, 0.001, 0.1),
        'type' => '',
        'start_date' => $faker->dateTimeThisMonth,
    ];
});
