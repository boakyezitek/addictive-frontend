<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditPacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_packs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('platform')->index();
            $table->string('reference');
            $table->float('amount', 8, 2);
            $table->integer('credits');
            $table->unique(['platform', 'credits']);
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
        Schema::dropIfExists('credit_packs');
    }
}
