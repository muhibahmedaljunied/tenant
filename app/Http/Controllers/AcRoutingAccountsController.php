<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\AcMaster;
use App\Models\AcSetting;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\AcCostCenBranche;
use App\Models\AcCostCenFieldAdd;
use Illuminate\Support\Facades\DB;
use App\Services\AcRouting\AcRoutingService;

class AcRoutingAccountsController extends Controller
{


    public function index(Request $request)
    {
        $this->authorize('accounts_routing.access');
        $business_id = request()->session()->get('user.business_id');
        // $business_id =1;
        $ac_setting = AcSetting::first();

        // $lastChildrenBranch = AcMaster::whereDoesntHave('parents')->selectRaw("account_number, concat(account_name_ar, ' (', account_number , ')') as account_name_number")->pluck('account_name_number', 'account_number')->toArray();
        $sale_accounts = AcMaster::getCashAccounts()->selectRaw("account_number, concat(account_name_ar, ' (', account_number , ')') as account_name_number")->pluck('account_name_number', 'account_number');
        $all_master = AcMaster::select([DB::raw("concat(account_name_ar, ' (', account_number , ')') as account_name_number"), 'account_number', 'parent_acct_no', 'account_type'])->get();

        // dd($business_id,$ac_setting,$sale_accounts,$all_master);
        $branch_cost_centers = AcCostCenBranche::forDropdown($business_id);
        $extra_cost_centers = AcCostCenFieldAdd::forDropdown($business_id);

        $can_update = auth()->user()->can('accounts_routing.update');
        $debtors = $all_master->where('account_type', 'debtor');
        $creditors = $all_master->where('account_type', 'creditor');

        $masterByType = [
            'creditor' => [
                'all' => $creditors->pluck('account_name_number', 'account_number')->toArray(),
                'empty_parent' => $creditors
                    ->filter(fn($item) => ! empty($item->parent_acct_no))
                    ->pluck('account_name_number', 'account_number')->toArray(),
            ],
            'debtor' => [
                'all' => $debtors->pluck('account_name_number', 'account_number')->toArray(),
                'empty_parent' => $debtors
                    ->filter(fn($item) => ! empty($item->parent_acct_no))
                    ->pluck('account_name_number', 'account_number')->toArray(),
            ],
            'both' => [
                'all' => $all_master->pluck('account_name_number', 'account_number')->toArray(),
                'empty_parent' => $all_master
                    ->filter(fn($item) => ! empty($item->parent_acct_no))
                    ->pluck('account_name_number', 'account_number')->toArray(),
            ],
        ];
        // dd(
        //     $sale_accounts
        // );

        $menuItems = $request->menuItems;
        return view('ac_settings.index', compact(
            'ac_setting',
            'masterByType',
            'can_update',
            'sale_accounts',
            'branch_cost_centers',
            'extra_cost_centers',
            'menuItems'
        ));
    }

    public function update(Request $request, $type)
    {
        $this->authorize('accounts_routing.update');
        $business_id = session('user.business_id');

        try {
            $ac_setting = AcSetting::where('business_id', $business_id)->first();

            $acRountingService = new AcRoutingService;
            switch ($type) {
                case 'income_statement':
                    $newData = $acRountingService->updateIncomeStatement($request);
                    break;
                case 'balance_sheet':
                    $newData = $acRountingService->updateBalanceSheet($request);
                    break;
                case 'purchases':
                    $newData = $acRountingService->updatePurchases($request);
                    break;
                case 'sales':
                    $newData = $acRountingService->updateSales($request);
                    break;
                case 'inventory_sec':
                    $newData = $acRountingService->updateInventory($request);
                    break;
                case 'treasury':
                    $newData = $acRountingService->updateTreasury($request);
                    break;
                case 'vat_due':
                    $newData = $acRountingService->updateVatDue($request);
                    break;
                case 'payment_methods':
                    $newData = $acRountingService->updatePaymentMethods($request);
                    break;
                case 'point_of_sale':
                    $newData = $acRountingService->updatePos($request);
                    break;
                case 'stocktaking':
                    $newData = $acRountingService->updateStockTakingInventory($request);
                    break;

                default:
                    abort(404);
                    break;
            }

            $ac_setting->fill($newData);
            $ac_setting->save();
            $output = [
                'success' => 1,
                'msg' => __('business.settings_updated_success')
            ];
        } catch (Exception $e) {

            logger()->emergency("File: {$e->getFile()} Line: {$e->getLine()} Message: {$e->getTraceAsString()}");

            $output = [
                'success' => 0,
                'msg' => __('messages.something_went_wrong')
            ];
        }
        return redirect(route('accounting.routing.index'))->with('status', $output);
    }
}
