<?php

use Illuminate\Database\Seeder;

class CampaignSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Campaign::class, 10)->create()->each(function ($model) {
            $limit = rand(1,10);
            $tags = \App\Models\Tag::inRandomOrder()->limit($limit)->get();
            $model->tags()->sync($tags);
        });
    }
}
