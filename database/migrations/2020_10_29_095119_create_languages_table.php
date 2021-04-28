<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLanguagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
            $table->timestamps();
        });

        Schema::table('audio_books', function(Blueprint $table) {
            $table->unsignedBigInteger('language_id')->index()->after('price')->nullable();
            $table->foreign('language_id')->on('languages')->references('id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('audio_books', function(Blueprint $table) {
            $table->dropForeign('audio_books_language_id_foreign');
            $table->dropColumn('language_id');

        });
        Schema::dropIfExists('languages');
    }
}
