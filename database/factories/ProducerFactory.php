<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Producer as Model;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;

$factory->define(Model::class, function (Faker $faker) {
    /* @var $query Illuminate\Database\Query\Builder */
    $userId = DB::table('users')->select('id')->inRandomOrder()->first()->id;
    $imageId = DB::table('files')->select('id')->inRandomOrder()->first()->id;
    return [
        'user_id' => $userId,
        'image_id' => $imageId,
        'name' => $faker->name,
        'description' => $faker->text,
        'website' => $faker->url,
    ];
});
