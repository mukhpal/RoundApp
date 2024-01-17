<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accountings', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('payment_account_id');
            $table->string('transaction_type', 30);
            $table->dateTime('date');
            $table->decimal('amount', 10, 5);
            $table->string('payment_account_type', 30);
            $table->string('payment_account_code',50);
            $table->string('payment_account_accountholder',300);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accountings');
    }
}
