<?php

namespace Modules\Inventory\Http\Controllers;

use Exception;
use App\{
    Unit,
    VariationLocationDetails,
    Brands,
    Product,
    Category,
    BusinessLocation,
    Transaction
};
use App\Models\AcSetting;

use Illuminate\Routing\Controller;
use Illuminate\Http\{Request, Response};
use Illuminate\Support\Facades\DB;
use Modules\Inventory\Entities\{Stockinglog, Stockingline, InventoryTransactions};
use Modules\Inventory\Http\Service\StockLineService;
use App\Services\JournalEntries\RecordEntriesTransaction;

class InventoryController extends Controller
{
    use RecordEntriesTransaction;

    public function __construct()
    {
        $this->middleware('can:inventory.stocking_create', ['only' => ['create', 'store', 'index']]);
        $this->middleware('can:inventory.stocking_edit', ['only' => ['changestatus']]);
        $this->middleware('can:inventory.stocking_delete', ['only' => ['delete_stock']]);
    }

    public function index(Request $request)
    {
        // close any open transaction
        if ($request->ajax()) {
            $transactions = InventoryTransactions::join('business_locations', 'business_locations.id', 'inventory_transactions.location_id')
                ->where('inventory_transactions.business_id', session('user.business_id'))
                ->select(
                    'inventory_transactions.*',
                    'business_locations.name as location_name'
                )->get();
            return view('inventory::partials.inventory_transactions', compact('transactions'))->render();
        }

       $menuItems =  $request->menuItems;
        return view('inventory::index',compact('menuItems'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $business_locations = BusinessLocation::forDropdown(session('user.business_id'));
        return view('inventory::create', compact('business_locations'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $business_id = session('user.business_id');
        $transactions = InventoryTransactions::where('location_id', $request->location_id)
            ->where('status', InventoryTransactions::STATUS_ON)->count();

        if ($transactions > 0) {
            return response()->json([
                'success' => false,
                'msg' => 'عفوا توجد فترة جرد لهذا الفرع'
            ]);
        }

        try {
            DB::beginTransaction();
            $transaction = Transaction::create([
                'business_id' => $business_id,
                'created_by' => auth()->id(),
                'transaction_date' => now(),
                'status' => 'final',
                'type' => 'stocktacking',
                'location_id' => $request->location_id,
            ]);

            $inventoryTransaction = InventoryTransactions::create([
                'business_id' => $business_id,
                'transaction_id' => $transaction->id,
                'location_id' => $request->location_id,
                'status' => $request->status,
                'transaction_date' => $request->start_date,
                'end_date' => $request->end_date,
                'created_by' => auth()->user()->id
            ]);

            $this->writelog($inventoryTransaction->id, 'إضافة فترة جرد');
            DB::commit();

            $output = [
                'success' => true,
                'msg' => 'تم اضافة الجرد بنجاح'
            ];


        } catch (Exception $e) {
            DB::rollBack();

            logger()->emergency("File: {$e->getFile()} Line: {$e->getLine()} Message: {$e->getMessage()}");

            $output = [
                'success' => 0,
                'msg' => __('messages.something_went_wrong')
            ];
        }

        return $output;
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('inventory::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('inventory::edit');
    }


    /* List all product for stocking  */
    public function stocking(Request $request)
    {
        if (! auth()->user()->can('inventory.stocking_products')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');
        $transaction_id = $request->id;
        $transaction = InventoryTransactions::where('inventory_transactions.id', $transaction_id)
            ->join('business_locations', 'business_locations.id', 'inventory_transactions.location_id')
            ->select('status', 'business_locations.name', 'business_locations.id as location_id')
            ->first();
        if ($transaction->status != InventoryTransactions::STATUS_ON) {
            $output = [
                'success' => 0,
                'msg' => 'خطأ، عملية جرد مغلقة '
            ];
            return redirect()->back()->with('status', $output);
        }
        $location_id = $transaction->location_id;
        if ($request->ajax()) {
            $query = Product::leftJoin('brands', 'products.brand_id', '=', 'brands.id')
                ->leftJoin('categories as c1', 'products.category_id', '=', 'c1.id')
                ->leftJoin('categories as c2', 'products.sub_category_id', '=', 'c2.id')
                ->join('variations as v', 'v.product_id', '=', 'products.id')
                ->leftJoin('variation_location_details as vld', 'vld.variation_id', '=', 'v.id')
                ->where('products.business_id', $business_id)
                ->where('products.type', '!=', 'modifier')
                ->where('vld.location_id', $location_id)
                ->leftJoin('stocktacking_lines', function ($join) use ($transaction_id) {
                    $join->on('v.id', '=', 'stocktacking_lines.variation_id')
                        ->where('stocktacking_lines.inventory_transaction_id', $transaction_id);
                })
                ->leftJoin('users', 'users.id', '=', 'stocktacking_lines.created_by');

            $query->select(
                'products.id',
                'v.id as variation_id',
                'products.name as product',
                'v.name as var_name',
                'v.dpp_inc_tax',
                'v.sell_price_inc_tax',
                'products.type',
                'c1.name as category',
                'c2.name as sub_category',
                'brands.name as brand',
                'products.sku',
                'products.image',
                'products.enable_stock',
                'products.is_inactive',
                'products.not_for_selling',
                'products.product_custom_field1',
                'products.product_custom_field2',
                'products.product_custom_field3',
                'products.product_custom_field4',
                'stocktacking_lines.updated_at',
                'users.first_name',
                'users.last_name',
                DB::raw('vld.qty_available as current_stock')
            )->active()->orderby('products.name');



            $stock_status = $request->stock_status;

            if ($stock_status == 1) {
                $query->whereNotNull('stocktacking_lines.updated_at');
                if (! empty($request->start_date)) {
                    $query->whereDate('stocktacking_lines.updated_at', '>=', $request->start_date)
                        ->whereDate('stocktacking_lines.updated_at', '<=', $request->end_date);
                }
            }

            if ($stock_status == 2) {
                $query->whereNull('stocktacking_lines.updated_at');
            }


            $query->when(request('type'), fn($q) => $q->where('products.type', request('type')));



            $current_stock = request('current_stock');
            $query->when($current_stock == 'zero', function ($q) {
                $q->having('current_stock', '0');
            });
            $query->when($current_stock == 'gtzero', function ($q) {
                $q->having('current_stock', '>', 0);
            });

            $query->when($current_stock == 'lszero', function ($q) {
                $q->having('current_stock', '<', 0);
            });



            $category_id = request()->get('category_id', null);
            if (! empty($category_id)) {
                $query->where('products.category_id', $category_id);
            }

            $brand_id = request()->get('brand_id', null);
            if (! empty($brand_id)) {
                $query->where('products.brand_id', $brand_id);
            }


            $unit_id = request()->get('unit_id', null);
            if (! empty($unit_id)) {
                $query->where('products.unit_id', $unit_id);
            }
            $searchtext = request()->get('searchtext');

            if (! empty($searchtext)) {
                $query->where('products.name', 'like', "%{$searchtext}%");
            }



            // $query->Active();

            $offset = $request->offset;
            $pagsize = $request->pagsize;
            $products = $query->offset($offset)->limit($pagsize)->get();
            $count = $products->count();
            $output['html_content'] = view('inventory::products', compact('products', 'offset'))->render();
            $output['success'] = true;
            return $output;
        }
        /*End of get products */

        $categories = Category::forDropdown($business_id, 'product');

        $brands = Brands::forDropdown($business_id);

        $units = Unit::forDropdown($business_id);
        return view('inventory::transaction_form', compact('transaction', 'transaction_id', 'categories', 'units', 'brands', 'location_id'));
    }

    public function changestatus(Request $request)
    {


        $transaction_id = $request->transaction_id;
        $status = $request->status;
        $new_staus = "off";
        if ($status == 0) {
            $new_staus = "on";
        }
        try {
            $transaction = InventoryTransactions::whereId($transaction_id)->first();

            if (empty($transaction)) {
                $output['success'] = false;
                $output['msg'] = 'عفوا قد يكون تم غلق الجرد';
                return $output;
            }

            if ($status == 0) {
                $transaction2 = InventoryTransactions::where('status', InventoryTransactions::STATUS_ON)
                    ->where('location_id', $transaction->location_id)
                    ->first();
                if (! empty($transaction2)) {
                    $output['success'] = false;
                    $output['msg'] = 'عفوا يوجد فترة جرد مفتوحة لهذا المخزن';
                    return $output;
                }
                $transaction->status = InventoryTransactions::STATUS_ON;
                $transaction->save();

                $output['success'] = true;
                $output['msg'] = 'تم فتح عملية الجرد بنجاح';

                $this->writelog($transaction->id, ' فتح عملية الجرد ');

                return $output;
            } else {
                $transaction->status = InventoryTransactions::STATUS_OFF;
                $transaction->save();
                $output['success'] = true;
                $output['msg'] = 'تم غلق عملية الجرد بنجاح';
                $this->writelog($transaction->id, ' غلق عملية الجرد ');
                return $output;
            }


        } catch (Exception $e) {
            logger()->emergency("File: {$e->getFile()} Line: {$e->getLine()} Message: {$e->getMessage()}");

            $output = [
                'success' => false,
                'msg' => __("messages.something_went_wrong")
            ];
        }
        return $output;
    }

    public function delete_stock(Request $request)
    {
        try {
            $stock = Stockingline::where('inventory_transaction_id', $request->transaction_id)->count();
            if ($stock > 0) {
                return [
                    'success' => false,
                    'msg' => __("inventory::lang.stock_product_found")
                ];
            }
            DB::transaction(function () use ($request) {
                InventoryTransactions::whereId($request->transaction_id)->delete();
                Stockinglog::where('inventory_transaction_id', $request->transaction_id)->delete();
            });
            $output = [
                'success' => true,
                'msg' => __("inventory::lang.transaction_deleted_success")
            ];
        } catch (Exception $e) {
            logger()->emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());
            $output = [
                'success' => false,
                'msg' => __("messages.something_went_wrong")
            ];
        }
        return $output;
    }

    public function stock_line_save(Request $request)
    {
        if (! auth()->user()->can('inventory.stocking_products')) {
            return [
                'success' => false,
                'msg' => __("messages.Unauthorized_action")
            ];
        }

        $transactin = InventoryTransactions::where('id', $request->transaction_id)->first();
        if ($transactin->status == "off") {
            return [
                'success' => false,
                'msg' => __("inventory::lang.transaction_of")
            ];

        }

        //save data
        $stoking = Stockingline::where('inventory_transaction_id', $request->transaction_id)
            ->where('product_id', $request->product_id)
            ->where('variation_id', $request->variation_id)
            ->first();
        $input[] = [
            'transaction_id' => $request->transaction_id,
            'product_id' => $request->product_id,
            'variation_id' => $request->variation_id
        ];

    }

    /* Get single product for create stocking*/
    public function getproduct(Request $request)
    {
        $transaction_id = $request->transaction_id;
        $product_id = $request->product_id;
        $variation_id = $request->variation_id;
        $location_id = $request->location_id;
        $product = Product::where('products.id', $product_id)
            ->join('variations', 'variations.product_id', 'products.id')
            ->select(
                'products.name as pro_name',
                'variations.name as var_name',
                'default_purchase_price',
                'product_variation_id',
                'sell_price_inc_tax',
                'products.type'
            )->first();

        $quantity = 0;
        $qty_available = 0;
        $location_name = "";
        $unit_price = 0;
        $rassed = VariationLocationDetails::where('variation_id', $variation_id)
            ->where('variation_location_details.location_id', $location_id)
            ->join('business_locations', 'variation_location_details.location_id', 'business_locations.id')
            ->select(
                'business_locations.name as location_name',
                'variation_location_details.qty_available'
            )->first();

        if (! empty($rassed)) {
            $quantity = $rassed->qty_available;
            $qty_available = $rassed->qty_available;
            $location_name = $rassed->location_name;
        }

        $old_stoking = Stockingline::where('inventory_transaction_id', $transaction_id)
            ->where('variation_id', $variation_id)
            ->first();
        if (! empty($old_stoking)) {
            $qty_available = $old_stoking->curent_quantity;
            $quantity = $old_stoking->new_quantity;
            $unit_price = $old_stoking->unit_price;
        }
        return view(
            'inventory::partials.product_stocking',
            compact(
                'quantity',
                'location_name',
                'product',
                'transaction_id',
                'unit_price',
                'variation_id',
                'location_id',
                'qty_available'
            )
        );
    }

    public function savestocking(Request $request)
    {
        if (! auth()->user()->can('inventory.stocking_products')) {
            return [
                'success' => false,
                'msg' => __("messages.Unauthorized_action")
            ];
        }
        DB::beginTransaction();

        $transaction_id = $request->transaction_id;
        $location_id = $request->location_id;
        $variation_id = $request->variation_id;
        $purchase_price = $request->purchase_price;
        $selling_price = $request->selling_price;
        $curent_quantity = $request->curent_quantity;
        $stock_quantity = $request->stock_quantity;
        $unit_price = $request->unit_price;

        $log_msg = "";
        try {
            $stockLine = (new StockLineService)->getStockLine($request);

            $log_msg = $stockLine->wasRecentlyCreated ? "إضافة جرد" : 'تعديل الجرد';


            /*Update */
            $variationLocation = VariationLocationDetails::where('variation_id', $variation_id)
                ->where('location_id', $location_id)->with('variation')->first();

            $variationLocation->update([
                'qty_available' => $stock_quantity
            ]);

            /* edit price */


            $variationLocation->variation->update([
                'sell_price_inc_tax' => $selling_price,
                'default_sell_price' => $selling_price,
                'dpp_inc_tax' => $request->purchase_price,
                'default_purchase_price' => $request->purchase_price,
            ]);

            /**/
            $acSettings = AcSetting::first();

            if (! is_null($acSettings->disability_inventory_id) && $curent_quantity > $stock_quantity) {
                $storeEntry = $this->storeEntry(
                    now()->day(),
                    "قيد عجز في جرد المخزون",
                );
                $amount = (abs($curent_quantity - $stock_quantity) * $purchase_price);
                $this->storeEntryDetails($storeEntry->id, $acSettings->inventory, $amount * -1);

                $this->storeEntryDetails($storeEntry->id, $acSettings->disability_inventory_id, $amount);

            } elseif (! is_null($acSettings->increase_inventory_id) && $curent_quantity < $stock_quantity) {
                $storeEntry = $this->storeEntry(
                    now()->day(),
                    "قيد زيادة في جرد المخزون",
                );
                $amount = (($stock_quantity - $curent_quantity) * $purchase_price);
                $this->storeEntryDetails($storeEntry->id, $acSettings->increase_inventory_id, $amount* -1);
                $this->storeEntryDetails($storeEntry->id, $acSettings->inventory, $amount );
            }
            // }

            $this->writelog(
                $transaction_id,
                $log_msg,
                $variation_id,
                2,
                $curent_quantity,
                $stock_quantity,
                $purchase_price,
                $unit_price
            );

            $output = [
                'success' => 1,
                'msg' => 'تم اضافة الكمية الفعلية بنجاح'
            ];
            DB::commit();

        } catch (Exception $e) {

            DB::rollBack();
            logger()->emergency("File: {$e->getFile()} Line: {$e->getLine()} Message: {$e->getMessage()}");

            $output = [
                'success' => 0,
                'msg' => __('messages.something_went_wrong')
            ];
        }
        return $output;
    }

    public function deletstock(Request $request)
    {

        return [
            'success' => false,
            'msg' => __('messages.something_went_wrong')
        ];

    }


    private function writelog(
        $transaction_id,
        $msg,
        $variation_id = null,
        $from = 1,
        $curent_quantity = 0,
        $new_quantity = 0,
        $dpp_inc_tax = 0,
        $sell_price_inc_tax = 0
    ) {
        Stockinglog::create([
            'business_id' => session('user.business_id'),
            'transaction_id' => $transaction_id,
            'type' => $from,
            'variation_id' => $variation_id,
            'curent_quantity' => $curent_quantity,
            'new_quantity' => $new_quantity,
            'dpp_inc_tax' => $dpp_inc_tax,
            'sell_price_inc_tax' => $sell_price_inc_tax,
            'created_by' => auth()->user()->id,
            'description' => $msg,
        ]);
        return true;
    }

}
