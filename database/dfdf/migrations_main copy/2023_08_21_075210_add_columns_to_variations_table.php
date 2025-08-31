<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToVariationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('variations', function (Blueprint $table) {
            // ------------------------------------- //
            $table->integer('equal_qty')->nullable();
            // ------------------------------------- //
            $table->unsignedInteger('unit_id')->nullable();
            $table->foreign('unit_id')->references('id')->on('units');
            // ------------------------------------- //
            $table->unsignedInteger('parent_variation_id')->nullable();
            $table->foreign('parent_variation_id')->references('id')->cascadeOnDelete()->on('variations');
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
        Schema::table('variations', function (Blueprint $table) {
            // ------------------------------------- //
            $table->dropForeign(['parent_variation_id']);
            // ------------------------------------- //
            $table->dropForeign(['unit_id']);
            // ------------------------------------- //
            $table->dropColumn('parent_variation_id');
            // ------------------------------------- //
            $table->dropColumn('unit_id');
            // ------------------------------------- //
            $table->dropColumn('equal_qty');
            // ------------------------------------- //
        });
    }
}
