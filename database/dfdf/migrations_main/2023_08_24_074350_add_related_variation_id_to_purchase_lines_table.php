<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelatedVariationIdToPurchaseLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_lines', function (Blueprint $table) {
            // ------------------------------------- //
            $table->unsignedInteger('related_variation_id')->nullable();
            $table->foreign('related_variation_id')->references('id')->cascadeOnDelete()->on('variations');
            // ------------------------------------- //
            $table->decimal('main_qty_purchased',22,4)->nullable();
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
        Schema::table('purchase_lines', function (Blueprint $table) {
            // ------------------------------------- //
            $table->dropForeign(['related_variation_id']);
            $table->dropColumn('related_variation_id');
            $table->dropColumn('main_qty_purchased');
            // ------------------------------------- //
        });
    }
}
