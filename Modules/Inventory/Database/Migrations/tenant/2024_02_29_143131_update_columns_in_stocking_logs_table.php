<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateColumnsInStockingLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('stocking_logs', function (Blueprint $table) {
        //     // -------------------------------------------- \\ 
        //     $table->unsignedInteger('business_id')->change();
        //     // -------------------------------------------- \\ 
        //     DB::statement("ALTER TABLE {$table->getTable()} RENAME COLUMN transaction_id TO inventory_transaction_id");
        //     // -------------------------------------------- \\ 
        //     // $table->text('description')->nullable()->change();
        //     // -------------------------------------------- \\ 
        // });
        // Schema::table('stocking_logs', function (Blueprint $table) {
        //     $table->unsignedBigInteger('inventory_transaction_id')->change();
        //     // -------------------------------------------- \\ 
        //     $table->foreign('business_id')->cascadeOnDelete()->references('id')->on('business');
        //     $table->foreign('inventory_transaction_id')->cascadeOnDelete()->references('id')->on('inventory_transactions');
        //     // -------------------------------------------- \\ 
        // });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // if (Schema::hasColumn('stocking_logs', 'inventory_transaction_id')) {
        //     Schema::table('stocking_logs', function (Blueprint $table) {
        //         // -------------------------------------------- \\ 
        //         $table->dropForeign(['inventory_transaction_id']);
        //         $table->dropForeign(['business_id']);
        //         // -------------------------------------------- \\ 
        //     });
        //     Schema::table('stocking_logs', function (Blueprint $table) {
        //         // -------------------------------------------- \\ 
        //         $table->integer('inventory_transaction_id')->change();
        //         // -------------------------------------------- \\ 
        //         DB::statement("ALTER TABLE {$table->getTable()} RENAME COLUMN inventory_transaction_id TO transaction_id");
        //         // -------------------------------------------- \\ 
        //     });
        // }
        // Schema::table('stocking_logs', function (Blueprint $table) {
        //     $table->integer('business_id')->change();
        //     // -------------------------------------------- \\ 
        //     $table->string('description', 255)->nullable()->change();
        //     // -------------------------------------------- \\ 
        // });
    }


}
