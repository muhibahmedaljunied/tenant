<?php
namespace App\Services\AcRouting;


use Illuminate\Http\Request;

class AcRoutingService
{
    public function updateIncomeStatement($request)
    {
        return $request->only([
            'operating_income',
            'direct_expenses',
            'non_operating_income',
            'operating_expenses',
            'non_operating_expenses',
            'sales_return'
        ]);
    }
    public function updateBalanceSheet($request)
    {
        return $request->only([
            'current_period_profit_loss',
            'stage_period_profit_loss',
            'assets',
            'liabilities',
            'equity'
        ]);
    }
    public function updatePurchases($request)
    {
        return $request->only([
            'suppliers'
        ]);
    }
    public function updateSales($request)
    {
        return $request->only([
            'customers',
            'sold_goods_cost',
            'sales_service_revenue',
            'sales_services_return'
        ]);
    }
    public function updateInventory($request)
    {
        return $request->only([
            'inventory'
        ]);
    }
    public function updateTreasury($request)
    {
        return $request->only('cash_equivalents');
    }
    public function updateVatDue($request)
    {
        return $request->only('vat_due');
    }
    public function updatePaymentMethods(Request $request): array
    {
        return $request->only([
            'payment_method_cash',
            'payment_method_card',
            'payment_method_cheque',
            'payment_method_bank_transfer',
            'payment_method_other',
            'payment_method_custom_pay_1',
        ]);
    }
    public function updatePos($request)
    {
        return $request->only([
            'branch_cost_center_id',
            'extra_cost_center_id'
        ]);
    }
    public function updateStockTakingInventory($request)
    {
        return $request->only([
            'disability_inventory_id',
            'increse_inventory_id'
        ]);
    }
}