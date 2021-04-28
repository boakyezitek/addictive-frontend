<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddisFreeSubscriptionToHomeSections extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('home_sections', function (Blueprint $table) {
            $table->boolean('is_free_subscription')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('home_sections', function (Blueprint $table) {
            $table->dropColumn('is_free_subscription');
        });
    }
}
