<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChapterUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chapter_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chapter_id')->index();
            $table->foreign('chapter_id')->on('chapters')->references('id');
            $table->unsignedBigInteger('user_id')->index();
            $table->foreign('user_id')->on('users')->references('id');
            $table->integer('time_elapsed')->default(0);
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
        Schema::dropIfExists('chapter_user');
    }
}
