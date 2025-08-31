<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameColumnTransactionIdInStocktackingLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // if (Schema::hasColumn('stocktacking_lines', 'transaction_id')) {
        //     Schema::table('stocktacking_lines', function (Blueprint $table) {
        //         // -------------------------------------------- \\ 
        //         // $table->renameColumn('transaction_id', 'inventory_transaction_id');
        //         // DB::statement("ALTER TABLE {$table->getTable()} RENAME COLUMN transaction_id TO inventory_transaction_id");

        //         // -------------------------------------------- \\ 
        //     });
        //     Schema::table('stocktacking_lines', function (Blueprint $table) {
        //         // $table->unsignedBigInteger('inventory_transaction_id')->change();
        //         // // -------------------------------------------- \\ 
        //         // $table->foreign('inventory_transaction_id')->references('id')
        //         //     ->on('inventory_transactions')
        //         //     ->cascadeOnDelete();
        //     });
        // }
        // if (Schema::hasColumn('stocktacking_lines', 'transaction_id')) {
        //     Schema::table('stocktacking_lines', function (Blueprint $table) {
        //         // -------------------------------------------- \\ 
        //         $table->unsignedBigInteger('transaction_id')->change();
        //         // -------------------------------------------- \\ 
        //         $table->foreign('transaction_id')->references('id')
        //             ->on('inventory_transactions')
        //             ->cascadeOnDelete();
        //         // -------------------------------------------- \\ 
        //         // -------------------------------------------- \\ 
        //     });
        // }
        // Schema::table('stocktacking_lines', function (Blueprint $table) {
        //     $table->renameColumn('transaction_id', 'inventory_transaction_id');
        // });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // if (Schema::hasColumn('stocktacking_lines', 'inventory_transaction_id')) {
        //     Schema::table('stocktacking_lines', function (Blueprint $table) {
        //         $table->dropForeign(['inventory_transaction_id']);
        //         // $table->dropColumn('inventory_transaction_id');
        //         // DB::statement("ALTER TABLE {$table->getTable()} RENAME COLUMN inventory_transaction_id TO transaction_id");

        //     });
        //     // Schema::table('stocktacking_lines', function (Blueprint $table) {
        //     // });
        // }
    }
}
