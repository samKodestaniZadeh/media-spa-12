<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('slug')->unique();
            $table->string('name')->unique()->fulltext();
            $table->string('name_en')->unique()->fulltext();
            $table->unsignedBigInteger('group')->nullable();
            $table->foreign('group')->references('id')->on('menus');
            $table->unsignedBigInteger('type')->nullable();
            $table->foreign('type')->references('id')->on('menus');
            $table->unsignedBigInteger('category')->nullable();
            $table->foreign('category')->references('id')->on('menus');
            $table->text('text');
            $table->string('demo_link');
            $table->unsignedBigInteger('price')->nullable();
            $table->text('version');
            $table->boolean('status')->default(0);
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
        Schema::dropIfExists('products');
    }
}
