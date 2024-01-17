<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\File as Model;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;

$factory->define(Model::class, function (Faker $faker) {
    /* @var $query Illuminate\Database\Query\Builder */
    $userId = DB::table('users')->select('id')->inRandomOrder()->first()->id;
    $dir = storage_path() . "/seed";
    if(!is_dir($dir))
        mkdir($dir, 777, true);
    $file = tempnam($dir, 'seed-');
    file_put_contents($file, $faker->text);
    $filesize = filesize($file);
    $mimeType = 'plain/text';
    $relativePath = "@storage/seed" . pathinfo($file, PATHINFO_FILENAME);
    $alg = "sha1";
    $hash = sha1_file($file);
    return [
        'user_id' => $userId,
        'type' => 'upload',
        'resource' => $relativePath,
        'size' => $filesize,
        'mime_type' => $mimeType,
        'original_name' => $faker->text(),
        'name' => $faker->text(),
        'description' => $faker->text,
        'hash_alg' => $alg,
        'hash_file' => $hash
    ];
});
