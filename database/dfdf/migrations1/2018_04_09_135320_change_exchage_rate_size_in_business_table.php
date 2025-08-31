<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ChangeExchageRateSizeInBusinessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // DB::statement("ALTER TABLE business ALTER COLUMN p_exchange_rate DECIMAL(20, 3) NOT NULL DEFAULT 1");
        // DB::statement("ALTER TABLE transactions ALTER COLUMN exchange_rate DECIMAL(20,3) NOT NULL DEFAULT 1");



        // ----------------------muhib add this-------------------------
        // Step 1: Alter column type and nullability
        DB::statement("ALTER TABLE business ALTER COLUMN p_exchange_rate DECIMAL(20, 3) NOT NULL");
        DB::statement("ALTER TABLE transactions ALTER COLUMN exchange_rate DECIMAL(20, 3) NOT NULL");

        // Step 2: Add default constraints
        // DB::statement("ALTER TABLE business ADD CONSTRAINT df_business_p_exchange_rate DEFAULT 1 FOR p_exchange_rate");
        // DB::statement("ALTER TABLE transactions ADD CONSTRAINT df_transactions_exchange_rate DEFAULT 1 FOR exchange_rate");
        // -----------------------------------------
        //Update 0 to 1
        DB::table('transactions')
            ->where('exchange_rate', 0)
            ->update(['exchange_rate' => 1]);
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
