<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('readers', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->timestamps();
        });

        Schema::create('audio_book_reader', function(Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('audio_book_id')->index();
            $table->foreign('audio_book_id')->on('audio_books')->references('id')->onDelete('cascade');
            $table->unsignedBigInteger('reader_id')->index();
            $table->foreign('reader_id')->on('readers')->references('id')->onDelete('cascade');
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
        Schema::dropIfExists('audio_book_reader');
        Schema::dropIfExists('readers');
    }
}
