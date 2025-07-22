<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepositsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deposits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('uuid')->nullable();
            $table->unsignedBigInteger('transactionId')->nullable();
            $table->string('transaction');
            $table->unsignedBigInteger('price');
            $table->string('bank_name')->nullable();
            $table->string('account_name')->nullable();
            $table->string('cart_number')->nullable();
            $table->string('code_p')->nullable();
            $table->string('code_e')->nullable();
            $table->timestamp('date')->nullable();
            $table->boolean('status')->default(0);
            $table->morphs('depositable');
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
        Schema::dropIfExists('deposits');
    }
}
