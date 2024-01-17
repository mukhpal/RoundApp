<?php

use Illuminate\Database\Seeder;

class UserSocialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\UserSocial::class, 10)->create();
    }
}
