<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('edisource_id')->nullable();
            $table->string('title');
            $table->text('description');
            $table->foreignId('ebook_id')->nullable()->constrained('ebooks')->onDelete('cascade');
            $table->foreignId('print_id')->nullable()->constrained('prints')->onDelete('cascade');
            $table->foreignId('audio_book_id')->nullable()->constrained('audio_books')->onDelete('cascade');
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
        Schema::dropIfExists('books');
    }
}
