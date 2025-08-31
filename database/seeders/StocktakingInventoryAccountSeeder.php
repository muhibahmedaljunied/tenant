<?php



use Illuminate\Database\Seeder;
use App\Services\AccountMaster\AccountMasterService;

class StocktakingInventoryAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $accountMasterService = new AccountMasterService();
        $accountMasterService->syncNewAccountToBusiness(51, 'تسوية عجز المخزون', 'debtor', 'disability_inventory_id');
        $accountMasterService->syncNewAccountToBusiness(42, 'زيادة جرد المخزون', 'debtor', 'increase_inventory_status');
    }
}
