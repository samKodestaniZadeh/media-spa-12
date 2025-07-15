<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDescriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('descriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('subject');
            $table->text('text');
            $table->boolean('status')->default(0);
            $table->timestamps();
        });

        Schema::create('descriptionables', function (Blueprint $table) {
            $table->unsignedBigInteger('description_id')->nullable();
            $table->foreign('description_id')->references('id')->on('descriptions');
            $table->morphs('descriptionable');
            $table->primary(['description_id','descriptionable_id']);
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
        Schema::dropIfExists('descriptions');
        Schema::dropIfExists('descriptionables');
    }
}
