<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableAudioBooksAddInernalCodeEbook extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('audio_books', function(Blueprint $table) {
            $table->string('internal_code_ebook')->nullable()->after('e_number');
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
            $table->dropColumn('internal_code_ebook');
        });
    }
}
