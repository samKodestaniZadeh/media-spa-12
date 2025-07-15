<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('routes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('name');
            $table->boolean('status')->default(0);
            $table->timestamps();
        });

        Schema::create('routeables', function (Blueprint $table) {
            $table->unsignedBigInteger('route_id')->nullable();
            $table->foreign('route_id')->references('id')->on('routes');
            $table->morphs('routeable');
            $table->primary(['route_id','routeable_id']);
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
        Schema::dropIfExists('routes');
        Schema::dropIfExists('routeables');
    }
}
