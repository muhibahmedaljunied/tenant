<?php
namespace Modules\Inventory\Http\Service;

// Modules\Inventory\Http\Controllers
use Modules\Inventory\Entities\Stockingline;

class StockLineService
{
    public function getStockLine($request)
    {
        $business_id = request()->session()->get('user.business_id');
        $userId = $request->session()->get('user.id');

        return Stockingline::updateOrCreate([
            'business_id' => $business_id,
            'inventory_transaction_id' => $request->transaction_id,
            'variation_id' => $request->variation_id,
        ], [
            'variation_id' => $request->variation_id,
            'inventory_transaction_id' => $request->transaction_id,
            'curent_quantity' => $request->curent_quantity,
            'new_quantity' => $request->stock_quantity,
            'dpp_inc_tax' => $request->purchase_price,
            'sell_price_inc_tax' => $request->selling_price,
            'unit_price' => $request->unit_price,
            'description' => $request->description,
            'business_id' => $business_id,
            'created_by' => $userId
        ]);
    }
}