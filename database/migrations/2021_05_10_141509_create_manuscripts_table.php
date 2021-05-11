<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManuscriptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manuscripts', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('address');
            $table->string('phone')->nullable();
            $table->boolean('have_pseudonym')->default(0);
            $table->string('pseudonym')->nullable();
            $table->text('presentation');
            $table->boolean('contract');
            $table->boolean('free_broadcast');
            $table->string('title');
            $table->string('genres');
            $table->integer('sign_number');
            $table->text('summary');
            $table->text('characters_summary');
            $table->text('plot');
            $table->text('additionnal_information');
            $table->boolean('cgu_accepted');
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
        Schema::dropIfExists('manuscripts');
    }
}
