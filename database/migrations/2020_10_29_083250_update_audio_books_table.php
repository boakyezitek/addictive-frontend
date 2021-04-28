<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAudioBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('audio_books', function(Blueprint $table) {
            $table->string('e_number')->nullable()->after('description');
            $table->string('internal_code')->nullable()->after('e_number');
            $table->string('isbn')->nullable()->after('internal_code');
            $table->string('recording_studio')->nullable()->after('isbn');
            $table->decimal('price', 6, 2)->nullable()->after('recording_studio');
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
            $table->dropColumn('e_number');
            $table->dropColumn('internal_code');
            $table->dropColumn('isbn');
            $table->dropColumn('recording_studio');
            $table->dropColumn('price');
        });
    }
}
