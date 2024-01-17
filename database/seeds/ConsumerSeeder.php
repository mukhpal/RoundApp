<?php

use Illuminate\Database\Seeder;

class ConsumerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Consumer::class, 10)->create()->each(function ($model) {
            $limit = rand(1,10);
            $tags = \App\Models\Tag::inRandomOrder()->limit($limit)->get();
            $model->tags()->sync($tags);
        });
    }
}
