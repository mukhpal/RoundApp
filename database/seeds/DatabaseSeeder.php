<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $this->call([
             UserSeeder::class,
             FileSeeder::class,
             TagSeeder::class,
             ConsumerSeeder::class,
             ProducerSeeder::class,
             UserSocialSeeder::class,
             AreaSeeder::class,
             VideoSeeder::class,
             PaymentAccountSeeder::class,
             AccountingSeeder::class,
             CampaignSeeder::class,
             InteractionSeeder::class,
         ]);
    }
}
