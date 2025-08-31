<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class StockAdjustmentMoveToTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // DB::statement("ALTER TABLE `transactions` CHANGE `type` `type` ENUM('purchase','sell','expense','stock_adjustment') DEFAULT NULL");


        // 🧹 Drop table if it exists
        DB::statement("IF OBJECT_ID('stock_adjustment_lines', 'U') IS NOT NULL DROP TABLE stock_adjustment_lines");

        // ✅ Create stock_adjustment_lines table
        Schema::create('stock_adjustment_lines', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('transaction_id');
            $table->foreign('transaction_id')->references('id')->on('transactions');

            $table->unsignedInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products');

            $table->unsignedInteger('variation_id');
            $table->foreign('variation_id')->references('id')->on('variations');

            $table->decimal('quantity', 22, 4);
            $table->decimal('unit_price', 22, 4)->nullable()->comment('Last purchase unit price');

            $table->timestamps();

            $table->index('transaction_id');
        });

        // ✅ Add columns to transactions table
        Schema::table('transactions', function (Blueprint $table) {
            // $table->string('adjustment_type', 20)->nullable()->after('payment_status');
            DB::statement("ALTER TABLE transactions ADD CONSTRAINT chk_adjustment_type CHECK (adjustment_type IN ('normal','abnormal'))");

            $table->decimal('total_amount_recovered', 22, 4)->nullable()->comment('Used for stock adjustment.')->after('exchange_rate');
        });

        // ✅ Create stock_adjustments table if not exists
        DB::statement("IF OBJECT_ID('stock_adjustments', 'U') IS NULL CREATE TABLE stock_adjustments (id INT NULL)");

        // ✅ Rename table
        DB::statement("EXEC sp_rename 'stock_adjustments', 'stock_adjustments_temp'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
