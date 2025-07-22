<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('parent_id')->nullable();
            $table->unsignedBigInteger('destination')->nullable();
            $table->foreign('destination')->references('id')->on('users');
            $table->unsignedBigInteger('recepiant');
            $table->foreign('recepiant')->references('id')->on('menus');
            $table->unsignedBigInteger('subject');
            $table->foreign('subject')->references('id')->on('menus');
            $table->text('text');
            $table->boolean('status')->default(0);
            $table->timestamps();
        });

        Schema::create('supportables', function (Blueprint $table) {
            $table->unsignedBigInteger('support_id');
            $table->foreign('support_id')->references('id')->on('supports');
            $table->morphs('supportable');
            $table->primary(['support_id','supportable_id']);
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
        Schema::dropIfExists('supports');
        Schema::dropIfExists('supportables');
    }
}
