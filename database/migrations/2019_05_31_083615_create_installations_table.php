<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstallationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('installations', function (Blueprint $table) {
            $table->uuid('uuid');
            $table->primary('uuid');
            $table->unsignedBigInteger('user_id')->unsigned()->nullable();
            $table->string('app_id');
            $table->string('app_version');
            $table->string('device_type');
            $table->string('locale', 20);
            $table->string('timezone');
            $table->string('os_version');
            $table->string('device_brand');
            $table->timestamps();
            $table->foreign('user_id')->on('users')->references('id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('installations');
    }
}
