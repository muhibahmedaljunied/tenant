<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTransactionIdColumnToInventoryTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('inventory_transactions', function (Blueprint $table) {
        //     // -------------------------------------------- \\ 
        //     // $table->unsignedInteger('transaction_id')->nullable()->after('business_id');
        //     // $table->foreign('transaction_id')->cascadeOnDelete()->references('id')->on('transactions');
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
        // Schema::table('inventory_transactions', function (Blueprint $table) {
        //     $table->removeColumn('transaction_id');
        // });
    }
}
