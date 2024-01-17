<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->integer('area_id')->nullable();
            $table->integer('producer_id');
            $table->integer('video_id')->nullable();
            $table->integer('accounting_id')->nullable();
            $table->string('title', 100);
            $table->text('description')->nullable();
            $table->string('geolocation', 2000)->nullable();
            $table->integer('min_age')->nullable();
            $table->integer('max_age')->nullable();
            $table->smallInteger('gender', false, true);
            $table->decimal('budget', 10, 5);
            $table->decimal('reward', 10, 5);
            $table->string('type', 30);
            $table->integer('people')->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
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
        Schema::dropIfExists('campaigns');
    }
}
