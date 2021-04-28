<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAudioBookAuthorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audio_book_author', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('audio_book_id')->index();
            $table->foreign('audio_book_id')->on('audio_books')->references('id')->onDelete('cascade');
            $table->unsignedBigInteger('author_id')->index();
            $table->foreign('author_id')->on('authors')->references('id')->onDelete('cascade');
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
        Schema::dropIfExists('audio_book_author');
    }
}
