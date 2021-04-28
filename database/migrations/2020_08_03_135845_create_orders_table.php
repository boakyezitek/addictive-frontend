<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->on('users')->references('id');
            $table->unsignedBigInteger('audio_book_id');
            $table->foreign('audio_book_id')->on('audio_books')->references('id');
            $table->unsignedBigInteger('transaction_id');
            $table->foreign('transaction_id')->on('transactions')->references('id');
            $table->unique(['user_id', 'audio_book_id']);
            $table->index(['user_id', 'audio_book_id']);
            $table->string('status')->default(\App\Models\Order::STATUS_WAITING_CONFIRMATION);
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
        Schema::dropIfExists('orders');
    }
}
