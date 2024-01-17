<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AddConstraints extends Migration
{
    const CONFIG = [
        /*
        'producers' => [
            'user_id', 'image_id' => ['refTable' => 'files']
        ],
        'consumers' => [
            'user_id'
        ],
        */
        'producers' => [
            'image_id' => ['refTable' => 'files']
        ],
        'campaigns' => [
            'area_id', 'producer_id', 'video_id', 'accounting_id'
        ],
        'videos' => [
            'file_id', 'thumbnail_id' => ['refTable' => 'files']
        ],
        'files' => [
            'user_id'
        ],
        'interactions' => [
            'accounting_id', 'consumer_id', 'campaign_id'
        ],
        'accountings' => [
            'user_id', 'payment_account_id'
        ],
        'payment_accounts' => [
            'user_id'
        ],
        'user_socials' => [
            'user_id',
        ],

        'consumer_tag' => [
            'consumer_id' => [ 'onDelete' => 'CASCADE'],
            'tag_id' => [ 'onDelete' => 'CASCADE']
        ],
        'campaign_tag' => [
            'campaign_id' => [ 'onDelete' => 'CASCADE'],
            'tag_id' => [ 'onDelete' => 'CASCADE']
        ],
    ];
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->addForeignKeys(static::CONFIG);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->dropForeignKeys(static::CONFIG);
    }

    /**
     * @param $tableName
     * @param $columns
     */
    protected function addForeignKey($tableName, $columns) {
        Schema::table($tableName, function (Blueprint $table) use($tableName, $columns) {
            foreach($columns as $k => $column) {
                $onUpdate = "CASCADE";
                $onDelete = "RESTRICT";
                if(is_scalar($column)) {
                    $chunks = explode("_", $column, -1);
                    $refEntity = implode("_", $chunks);
                    $refTable = Str::plural($refEntity);
                    $refColumns = str_replace("{$refEntity}_", "", $column);
                    $columnName = $column;
                }
                else {
                    $refColumns = $column['refColumns'] ?? 'id';
                    $onUpdate = $column['onUpdate'] ?? $onUpdate;
                    $onDelete = $column['onDelete'] ?? $onDelete;
                    $columnName = $column['column'] ?? $k;
                    if(isset($column['refTable'])) {
                        $refTable = $column['refTable'];
                    }
                    else {
                        $chunks = explode("_", $columnName, -1);
                        $refEntity = implode("_", $chunks);
                        $refTable = Str::plural($refEntity);
                    }
                }
                $table->foreign($columnName)->references($refColumns)->on($refTable)->onUpdate($onUpdate)->onDelete($onDelete);
            }
        });
    }

    /**
     * @param $tables
     */
    protected function addForeignKeys($tables) {
        foreach($tables as $table => $columns) {
            $this->addForeignKey($table, $columns);
        }
    }

    /**
     * @param $tableName
     * @param $columns
     */
    protected function dropForeignKey($tableName, $columns) {
        Schema::table($tableName, function (Blueprint $table) use($tableName, $columns) {
            foreach($columns as $k => $column) {
                $name = null;
                if(is_scalar($column)) {
                    $columnName = $column;
                    $name = implode('_', [$tableName, $column, "foreign"]);
                }
                else {
                    $columnName = $k;
                    if(key_exists('index', $column))
                        $name = $column['index'];
                }
                $name = $name ?? implode('_', [$tableName, $columnName, "foreign"]);
                $table->dropForeign($name);
            }
        });
    }

    /**
     * @param $tables
     */
    protected function dropForeignKeys($tables) {
        foreach($tables as $table => $columns) {
            $this->dropForeignKey($table, $columns);
        }
    }
}
