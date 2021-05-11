<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWebDurationToAudioBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('audio_books', function (Blueprint $table) {
            $table->integer('web_duration')->nullable();
            $table->dateTime('release_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('audio_books', function (Blueprint $table) {
            $table->dropColumn('web_duration');
            $table->dropColumn('release_date');
        });
    }
}
