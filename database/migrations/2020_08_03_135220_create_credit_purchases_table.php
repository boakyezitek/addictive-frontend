<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditPurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_purchases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->foreign('user_id')->on('users')->references('id');
            $table->unsignedBigInteger('credit_pack_id');
            $table->foreign('credit_pack_id')->on('credit_packs')->references('id');
            $table->index(['user_id', 'credit_pack_id'], 'credit_pack_user_index');
            $table->string('status')->default('waiting_confirmation');
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
        Schema::dropIfExists('credit_purchases');
    }
}
