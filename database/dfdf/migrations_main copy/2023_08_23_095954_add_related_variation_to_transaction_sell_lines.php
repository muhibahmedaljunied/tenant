<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelatedVariationToTransactionSellLines extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transaction_sell_lines', function (Blueprint $table) {
            // ------------------------------------- //
            $table->unsignedInteger('related_variation_id')->nullable();
            $table->foreign('related_variation_id')->references('id')->cascadeOnDelete()->on('variations');
            // ------------------------------------- //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transaction_sell_lines', function (Blueprint $table) {
            // ------------------------------------- //
            $table->dropForeign(['related_variation_id']);
            $table->dropColumn('related_variation_id');
            // ------------------------------------- //
        });
    }
}
