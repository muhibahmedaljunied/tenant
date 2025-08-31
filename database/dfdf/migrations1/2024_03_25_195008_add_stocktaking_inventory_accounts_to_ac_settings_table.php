<?php

use App\Services\AccountMaster\AccountMasterService;
use Illuminate\Database\{
    Migrations\Migration,
    Schema\Blueprint
};
use App\Models\AcMaster;
use Illuminate\Support\Facades\Schema;

class AddStocktakingInventoryAccountsToAcSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $accountMasterService = new AccountMasterService();

        $accountMasterService->setNewMasterAccount(
            null,
            51,
            5104,
            'تسوية عجز المخزون',
            'debtor'
        );
        $accountMasterService->setNewMasterAccount(
            null,
            42,
            4202,
            'زيادة جرد المخزون',
            'debtor'
        );


        Schema::table('ac_settings', function (Blueprint $table) {
            // -------------------------------------------------- //
            $table->bigInteger('disability_inventory_id')->default(5104)->comment('عجز_الجرد');
            $table->bigInteger('increase_inventory_id')->default(4202)->comment('زيادة_الجرد');
            // -------------------------------------------------- //
            $table->boolean('disability_inventory_status')->default(1);
            $table->boolean('increase_inventory_status')->default(1);
            // -------------------------------------------------- //
            $table->foreign('disability_inventory_id')->references('account_number')->on('ac_masters');
            $table->foreign('increase_inventory_id')->references('account_number')->on('ac_masters');
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
            $table->dropForeign(['disability_inventory_id']);
            $table->dropForeign(['increase_inventory_id']);
            // ----------------------------------------------------- \\
            $table->dropColumn([
                'increase_inventory_status',
                'increase_inventory_id',
                'disability_inventory_status',
                'disability_inventory_id'
            ]);
        });
    }
}
