<?php

use Illuminate\Database\Seeder;

class PaymentAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\PaymentAccount::class, 10)->create();
    }
}
