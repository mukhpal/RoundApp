<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class InsertIntoTagsTable extends Migration
{
    public $table = 'tags';

    public $tags = [
        'allenamento' => 'Allenamento',
        'animali' => 'Animali',
        'beni' => 'Beni',
        'casa' => 'Casa',
        'cibo' => 'Cibo',
        'finanza' => 'Finanza',
        'intrattenimento' => 'Intrattenimento',
        'moda' => 'Moda',
        'motori' => 'Motori',
        'sport' => 'Sport',
        'tecnologia' => 'Tecnologia',
        'viaggi' => 'Viaggi'
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach ($this->tags as $code => $description) {
            DB::table($this->table)->insert([
                'code' => $code,
                'description' => $description,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table($this->table)->truncate();
    }
}
