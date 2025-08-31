<?php

use App\Models\AcMaster;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToAcSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * 110301 حركات المخزون
     * 110302 مخزون اول المده
     * 110303 مخزون اخر المده
     * @return void
     */
    public function up()
    {
        AcMaster::withoutEvents(function () {
            AcMaster::firstOrCreate(['account_number' => 110302, 'business_id' => null], [
                'account_name_ar' => 'مخزون اول المده',
                'business_id' => null,
                'account_number' => 110302,
                'account_name_en' => '',
                'parent_acct_no' => 1103,
                'account_type' => 'debtor',
                'transaction_made' => 0,
                'archived' => 0,
                'account_status' => 'active',
                'account_level' => 3
            ]);
            AcMaster::firstOrCreate(['account_number' => 110303, 'business_id' => null], [
                'account_name_ar' => 'مخزون اخر المده',
                'account_name_en' => '',
                'business_id' => null,

                'parent_acct_no' => 1103,
                'account_type' => 'debtor',
                'transaction_made' => 0,
                'archived' => 0,
                'account_status' => 'active',
                'account_level' => 3
            ]);
        });
        Schema::table('ac_settings', function (Blueprint $table) {
            // --------------------------------------------------
            $table->bigInteger('transactions_inventory')->default(110301)->comment('حركات المخزون');
            $table->foreign('transactions_inventory')->references('account_number')->on('ac_masters')->cascadeOnDelete();
            $table->boolean('transactions_inventory_status')->default(1);
            // --------------------------------------------------
            $table->bigInteger('beginning_inventory')->default(110302)->comment('مخزون اول المده');
            $table->foreign('beginning_inventory')->references('account_number')->on('ac_masters')->cascadeOnDelete();
            $table->boolean('beginning_inventory_status')->default(1);
            // --------------------------------------------------
            $table->bigInteger('ending_stock_inventory')->default(110303)->comment('مخزون اخر المده');
            $table->foreign('ending_stock_inventory')->references('account_number')->on('ac_masters')->cascadeOnDelete();
            $table->boolean('ending_stock_inventory_status')->default(1);
            // -------------------------------------------------- //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ac_settings', function (Blueprint $table) {
            $table->dropForeign(['transactions_inventory']);
            $table->dropColumn('transactions_inventory');
            $table->dropColumn('transactions_inventory_status');
            // --------------------------------------------------------- \\ 
            $table->dropForeign(['beginning_inventory']);
            $table->dropColumn('beginning_inventory');
            $table->dropColumn('beginning_inventory_status');
            // --------------------------------------------------------- \\ 
            $table->dropForeign(['ending_stock_inventory']);
            $table->dropColumn('ending_stock_inventory');
            $table->dropColumn('ending_stock_inventory_status');
            // --------------------------------------------------------- \\ 
        });
    }
}
