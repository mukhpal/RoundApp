<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Area as Model;
use Faker\Generator as Faker;

$factory->define(Model::class, function (Faker $faker) {
    return [
        'type' => '',
        'address' => $faker->address,
        'latitude' => $faker->latitude,
        'longitude' => $faker->longitude,
        'radius' => $faker->randomFloat(2, 0, 100),
        'description' => $faker->text,
        'data' => json_encode([]),
    ];
});
