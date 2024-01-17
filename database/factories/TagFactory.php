<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Tag as Model;
use Faker\Generator as Faker;

$factory->define(Model::class, function (Faker $faker) {
    return [
        'code' => $faker->word,
        'description' => $faker->word
    ];
});
