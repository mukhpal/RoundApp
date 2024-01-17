<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\PaymentAccount as Model;
use Faker\Generator as Faker;

$factory->define(Model::class, function (Faker $faker) {
    $userId = DB::table('users')->select('id')->inRandomOrder()->first()->id;
    return [
        'user_id' => $userId,
        'type' => '',
        'code' => $faker->text(50),
        'check' => $faker->numberBetween(100, 999),
        'accountholder' => $faker->name,
        'data' => json_encode([]),
    ];
});
