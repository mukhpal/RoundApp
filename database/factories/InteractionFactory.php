<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Interaction as Model;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;

$factory->define(Model::class, function (Faker $faker) {
    $accountingId = optional(DB::table('accountings')->select('id')->inRandomOrder()->first())->id;
    $consumerId = optional(DB::table('consumers')->select('id')->inRandomOrder()->first())->id;
    $campaignId = optional(DB::table('campaigns')->select('id')->inRandomOrder()->first())->id;
    return [
        'accounting_id' => $accountingId,
        'consumer_id' => $consumerId,
        'campaign_id' => $campaignId,
        'type' => '',
        'data' => json_encode([]),
    ];
});
