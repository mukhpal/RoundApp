<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Video as Model;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;

$factory->define(Model::class, function (Faker $faker) {
    $fileId = DB::table('files')->select('id')->inRandomOrder()->first()->id;
    $thumbnailId = DB::table('files')->select('id')->inRandomOrder()->first()->id;
    return [
        'file_id' => $fileId,
        'thumbnail_id' => $thumbnailId,
        'title' => $faker->title,
        'url' => $faker->url,
        'duration' => $faker->numberBetween(3, 600),
        'data' => json_encode([])
    ];
});
