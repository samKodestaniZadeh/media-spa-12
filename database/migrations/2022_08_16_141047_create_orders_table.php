<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('price');
            $table->integer('count');
            $table->integer('discount');
            $table->integer('coupon');
            $table->integer('total');
            $table->integer('tax');
            $table->integer('col');
            $table->integer('payment');
            $table->integer('balance');
            $table->string('status')->default(0);
            $table->timestamps();
        });

        Schema::create('orderables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id')->references('id')->on('orders');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('price');
            $table->integer('count');
            $table->integer('discount');
            $table->integer('coupon');
            $table->integer('total');
            $table->integer('comison');
            $table->integer('tax');
            $table->integer('col');
            $table->morphs('orderable');
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
        Schema::dropIfExists('orders');
        Schema::dropIfExists('orderables');
    }
}
