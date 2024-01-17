<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('uuid', 100);
            $table->integer('user_id');
            $table->string('type', 30);
            $table->string('path', 2000);
            $table->integer('size');
            $table->string('mime_type', 100);
            $table->string('original_name', 300);
            $table->string('name', 300);
            $table->text('description');
            $table->string('hash_alg', 100);
            $table->string('hash_file', 300);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('files');
    }
}
