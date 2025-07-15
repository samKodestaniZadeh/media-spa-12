<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->bigInteger('wallet')->default(0);
            $table->string('ostan')->nullable();
            $table->string('shahr')->nullable();
            $table->string('address')->nullable();
            $table->date('birth')->nullable();
            $table->string('gender')->nullable();
            $table->string('biography')->nullable();
            $table->string('notification')->nullable();
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();
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
        Schema::dropIfExists('profiles');
    }
}
