<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Accounting as Model;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;

$factory->define(Model::class, function (Faker $faker) {
    $userId = DB::table('users')->select('id')->inRandomOrder()->first()->id;
    $paymentAccount = \App\Models\PaymentAccount::inRandomOrder()->first();
    return [
        'user_id' => $userId,
        'payment_account_id' => $paymentAccount->id,
        'transaction_type' => '',
        'date' => $faker->dateTimeThisMonth,
        'amount' => $faker->randomFloat(2, 100, 1000),
        'payment_account_type' => $paymentAccount->type,
        'payment_account_code' => $paymentAccount->code,
        'payment_account_accountholder' => $paymentAccount->accountholder,
    ];
});
