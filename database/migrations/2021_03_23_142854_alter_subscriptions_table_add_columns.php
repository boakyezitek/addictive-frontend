<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterSubscriptionsTableAddColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropForeign('subscriptions_plan_id_foreign');
            $table->dropColumn('plan_id');
            $table->string('interval')->default('monthly');
            $table->string('reference');
            $table->string('transaction_id');
            $table->float('price', 8, 2);
            $table->string('currency');
            $table->dateTime('purchased_at')->nullable();
            $table->dateTime('expiration_at')->nullable();
            $table->dateTime('renewed_at')->nullable();
            $table->integer('renewed_count')->default(0);
            $table->dateTime('cancelled_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->unsignedBigInteger('plan_id')->nullable();
            $table->foreign('plan_id')->on('plans')->references('id');
            $table->dropColumn('reference');
            $table->dropColumn('transaction_id');
            $table->dropColumn('price');
            $table->dropColumn('currency');
            $table->dropColumn('purchased_at');
            $table->dropColumn('expiration_at');
            $table->dropColumn('renewed_at');
            $table->dropColumn('renewed_count');
            $table->dropColumn('interval');
            $table->dropColumn('cancelled_at');
        });
    }
}
