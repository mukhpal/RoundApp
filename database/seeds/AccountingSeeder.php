<?php

use Illuminate\Database\Seeder;

class AccountingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Accounting::class, 10)->create();
    }
}
