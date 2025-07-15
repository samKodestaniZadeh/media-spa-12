<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('title')->fulltext();
            $table->string('title_en')->unique()->fulltext();
            $table->string('slug')->unique()->fulltext();
            $table->unsignedBigInteger('group')->nullable();
            $table->foreign('group')->references('id')->on('menus');
            $table->unsignedBigInteger('type')->nullable();
            $table->foreign('type')->references('id')->on('menus');
            $table->unsignedBigInteger('category')->nullable();
            $table->foreign('category')->references('id')->on('menus');
            $table->text('text');
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
        Schema::dropIfExists('blogs');
    }
}
