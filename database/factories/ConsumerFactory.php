<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Consumer as Model;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;

$factory->define(Model::class, function (Faker $faker) {
    $userId = DB::table('users')->select('id')->inRandomOrder()->first()->id;
    $gender = $faker->numberBetween(0, 2);
    $genderLabels = [
        null,
        'male',
        'female'
    ];
    return [
        'user_id' => $userId,
        'name' => $faker->name($genderLabels[$gender]),
        'surname' => $faker->lastName,
        'birth_year' => $faker->year,
        'gender' => $gender,
    ];
});
