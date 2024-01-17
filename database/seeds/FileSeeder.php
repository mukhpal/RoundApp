<?php

use Illuminate\Database\Seeder;

class FileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\File::class, 10)->create([
            'type' => 'video'
        ]);

        factory(App\Models\File::class, 10)->create([
            'type' => 'thumbnail'
        ]);

        factory(App\Models\File::class, 10)->create([
            'type' => 'avatar'
        ]);
    }
}
