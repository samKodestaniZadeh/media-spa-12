<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTarahisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tarahis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->foreign('company_id')->references('id')->on('companies');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('reqdesigner_id')->nullable();
            $table->unsignedBigInteger('canceller_id')->nullable();
            $table->foreign('canceller_id')->references('id')->on('users');
            $table->string('notification_id')->nullable();
            $table->foreign('notification_id')->references('id')->on('notifications');
            $table->string('date')->nullable();
            $table->string('title')->fulltext();
            $table->text('text');
            $table->unsignedBigInteger('price')->nullable();
            $table->integer('total')->nullable();
            $table->unsignedBigInteger('price_block')->nullable();
            $table->boolean('status')->default(0);
            $table->string('slug')->unique();
            $table->unsignedBigInteger('group')->nullable();
            $table->foreign('group')->references('id')->on('menus');
            $table->unsignedBigInteger('type')->nullable();
            $table->foreign('type')->references('id')->on('menus');
            $table->unsignedBigInteger('category')->nullable();
            $table->foreign('category')->references('id')->on('menus');
            $table->timestamp('expired_at')->nullable();
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
        Schema::dropIfExists('tarahis');
    }
}
