<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\UserSocial as Model;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;

$factory->define(Model::class, function (Faker $faker) {
    $userId = DB::table('users')->select('id')->inRandomOrder()->first()->id;
    return [
        'user_id' => $userId,
        'type' => '',
        'social_id' => $faker->randomElement(['facebook', 'google', 'apple']),
        'data' => json_encode([]),
    ];
});
