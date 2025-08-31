<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id();
            // -------------------------------------------- \\ 
            $table->integer('business_id')->nullable();
            $table->integer('location_id')->nullable();
            // -------------------------------------------- \\ 
            $table->string('status', 5)->nullable();
            // -------------------------------------------- \\ 
            $table->unsignedInteger('transaction_id')->nullable();
            $table->foreign('transaction_id')->references('id')->on('transactions')->cascadeOnDelete();
            // -------------------------------------------- \\ 
            $table->integer('created_by')->nullable();
            // -------------------------------------------- \\ 
            $table->timestamp('end_date')->nullable();
            $table->timestamp('transaction_date')->nullable();
            // -------------------------------------------- \\ 
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
        Schema::dropIfExists('inventory_transactions');
    }
}
