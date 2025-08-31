<?php

namespace Modules\Inventory\Http\Controllers;

use Datatables;
use Exception;

use App\{
    Unit,
    User,
    Brands,
    Product,
    Category,
    Variation,
    Transaction,
    BusinessLocation,
    VariationLocationDetails
};
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Inventory\Entities\{Stockinglog, Stockingline, InventoryTransactions};


class StocktackingController extends Controller
{
    public function __construct() {}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function report(Request $request)
    {
        if (! auth()->user()->can('stocktacking.products')) {
            abort(403, 'Unauthorized action.');
        }
        $business_id = request()->session()->get('user.business_id');
        $transaction_id = $request->id;
        $transaction = InventoryTransactions::where('inventory_transactions.id', $transaction_id)
            ->join('business_locations', 'business_locations.id', 'inventory_transactions.location_id')
            ->select('status', 'business_locations.name', 'business_locations.id as location_id')
            ->first();
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
                ->Join('stocktacking_lines', function ($join) use ($transaction_id) {
                    $join->on('v.id', '=', 'stocktacking_lines.variation_id')
                        ->where('stocktacking_lines.inventory_transaction_id', $transaction_id);
                })
                ->leftJoin('users', 'users.id', '=', 'stocktacking_lines.created_by');

            $query->select(
                'products.id',
                'v.id as variation_id',
                'products.name as product',
                'v.name as var_name',
                'products.type',
                'c1.name as category',
                'c2.name as sub_category',
                'brands.name as brand',
                'products.sku',
                'products.image',
                'products.not_for_selling',
                'stocktacking_lines.updated_at',
                'users.first_name',
                'users.last_name',
                'stocktacking_lines.curent_quantity',
                'stocktacking_lines.new_quantity',
                'unit_price',
                DB::raw('(curent_quantity-new_quantity) as stock_def'),
                DB::raw('(new_quantity-curent_quantity)*unit_price as total_def'),
                DB::raw('vld.qty_available as current_stock')
            );

            $stock_status = $request->stock_status;
            if ($stock_status == 1) {
                $query->whereNotNull('stocktacking_lines.updated_at');
            }

            if (! empty($request->start_date)) {
                $query->whereDate('stocktacking_lines.updated_at', '>=', $request->start_date)
                    ->whereDate('stocktacking_lines.updated_at', '<=', $request->end_date);
            }

            if ($stock_status == 2) {
                $query->whereNull('stocktacking_lines.updated_at');
            }

            $type = request()->get('type', null);
            if (! empty($type)) {
                $query->where('products.type', $type);
            }


            $current_stock = request()->get('current_stock', null);
            if ($current_stock == 'zero') {
                $query->having('stock_def', '0');
            }
            if ($current_stock == 'gtzero') {
                $query->having('stock_def', '<', 0);
            }
            if ($current_stock == 'lszero') {
                $query->having('stock_def', '>', 0);
            }

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
            $query->Active();
            $products = $query->get();
            $output['html_content'] = view('inventory::partials.products_report', compact('products'))->render();
            $output['success'] = true;
            return $output;
        }
        /*End of get products */

        $categories = Category::forDropdown($business_id, 'product');

        $brands = Brands::forDropdown($business_id);

        $units = Unit::forDropdown($business_id);
        return view('inventory::transaction_report', compact('transaction', 'transaction_id', 'categories', 'units', 'brands', 'location_id'));
    }

    public function details_report(Request $request)
    {
        if (! auth()->user()->can('stocktacking.products')) {
            abort(403, 'Unauthorized action.');
        }
        $business_id = request()->session()->get('user.business_id');
        $transaction_id = $request->id;


        if ($request->ajax()) {
            $query = Stockinglog::leftjoin('variations as v', 'v.id', 'stocking_logs.variation_id')
                ->leftjoin('products', 'products.id', 'v.product_id')
                ->leftJoin('categories as c1', 'products.category_id', '=', 'c1.id')
                ->leftJoin('categories as c2', 'products.sub_category_id', '=', 'c2.id')
                ->leftJoin('brands', 'products.brand_id', '=', 'brands.id')
                ->leftJoin('users', 'users.id', '=', 'stocking_logs.created_by')
                ->leftJoin('inventory_transactions', 'inventory_transactions.id', '=', 'stocking_logs.inventory_transaction_id')
                ->leftJoin('business_locations', 'business_locations.id', '=', 'inventory_transactions.location_id')
                ->select(
                    'stocking_logs.updated_at',
                    'stocking_logs.description',
                    'products.id',
                    'v.id as variation_id',
                    'products.name as product',
                    'v.name as var_name',
                    'products.type',
                    'c1.name as category',
                    'c2.name as sub_category',
                    'brands.name as brand',
                    'products.sku',
                    'products.image',
                    'products.not_for_selling',
                    'users.first_name',
                    'users.last_name',
                    'stocking_logs.curent_quantity',
                    'stocking_logs.new_quantity',
                    'business_locations.name as location_name',
                    'stocking_logs.sell_price_inc_tax as unit_price',
                    DB::raw('(curent_quantity-new_quantity) as stock_def'),
                    DB::raw('(new_quantity-curent_quantity)*stocking_logs.sell_price_inc_tax as total_def')
                );

            $stock_status = $request->stock_status;
            $type = request()->get('type', null);
            if (! empty($type)) {
                $query->where('products.type', $type);
            }

            if (! empty($request->start_date)) {
                $query->whereDate('stocking_logs.updated_at', '>=', $request->start_date)
                    ->whereDate('stocking_logs.updated_at', '<=', $request->end_date);
            }
            $current_stock = request()->get('current_stock', null);
            if ($current_stock == 'zero') {
                $query->having('stock_def', '0');
            }
            if ($current_stock == 'gtzero') {
                $query->having('stock_def', '<', 0);
            }
            if ($current_stock == 'lszero') {
                $query->having('stock_def', '>', 0);
            }

            $category_id = request()->get('category_id', null);
            if (! empty($category_id)) {
                $query->where('products.category_id', $category_id);
            }

            $brand_id = request()->get('brand_id', null);
            if (! empty($brand_id)) {
                $query->where('products.brand_id', $brand_id);
            }


            $user = request()->get('user', null);
            if (! empty($user)) {
                $query->where('stocking_logs.created_by', $user);
            }
            $locations = request()->get('locations', null);
            if (! empty($locations)) {
                $query->where('inventory_transactions.location_id', $locations);
            }

            $offset = $request->offset;
            $pagsize = $request->pagsize;
            $products = $query->offset($offset)->limit($pagsize)->get();
            $count = $products->count();
            $menuItems =  $request->menuItems;
            $output['html_content'] = view('inventory::partials.products_report_details', compact('products', 'offset', 'menuItems'))->render();
            $output['success'] = true;
            $output['count'] = $count;
            return $output;
        }
        /*End of get products */
        $menuItems =  $request->menuItems;
        $categories = Category::forDropdown($business_id, 'product');

        $brands = Brands::forDropdown($business_id);

        $units = Unit::forDropdown($business_id);
        $users = User::forDropdown($business_id);
        $locations = BusinessLocation::forDropdown($business_id);
        return view('inventory::details_report', compact(
            'categories',
            'units',
            'brands',
            'users',
            'locations',
            'menuItems'
        ));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function create()
    // {
    //     if (! auth()->user()->can('stocktacking.create')) {
    //         abort(403, 'Unauthorized action.');
    //     }
    //     $business_id = request()->session()->get('user.business_id');
    //     $business_locations = BusinessLocation::forDropdown($business_id);

    //     return view('stocktacking.create')
    //         ->with(compact('business_locations'));
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $request)
    // {
    //     if (! auth()->user()->can('stocktacking.create')) {
    //         abort(403, 'Unauthorized action.');
    //     }
    //     $business_id = request()->session()->get('user.business_id');
    //     $transactions = Transaction::where('type', 'stocktacking')->where('business_id', $business_id)->whereBetween('transaction_date', [$request->start_date, $request->end_date])->orWhereBetween('end_date', [$request->start_date, $request->end_date])->where('location_id', $request->location_id)->get();
    //     if (count($transactions) > 0) {

    //         $output = [
    //             'success' => 0,
    //             'msg' => 'تتعارض فترة الجرد مع عملية جرد اخري لهذا الفرع      '
    //         ];
    //         return redirect()->back()->with('status', $output);

    //     }
    //     try {
    //         Transaction::create([
    //             'business_id' => $business_id,
    //             'location_id' => $request->location_id,
    //             'type' => 'stocktacking',
    //             'status' => $request->status,
    //             'transaction_date' => $request->start_date,
    //             'end_date' => $request->end_date,
    //             'created_by' => auth()->user()->id
    //         ]);
    //         $output = [
    //             'success' => 1,
    //             'msg' => 'تم اضافة الجرد بنجاح'
    //         ];

    //     } catch (Exception $e) {
    //         DB::rollBack();
    //         logger()->emergency("File: {$e->getFile()} Line: {$e->getLine()} Message: {$e->getMessage()}");

    //         $output = [
    //             'success' => 0,
    //             'msg' => __('messages.something_went_wrong')
    //         ];
    //     }
    //     return redirect('stocktacking')->with('status', $output);
    // }





    public function transaction($id)
    {
        if (! auth()->user()->can('stocktacking.products')) {
            abort(403, 'Unauthorized action.');
        }
        $transaction = Transaction::findOrFail($id);
        if ($transaction->status != 'on' || $transaction->end_date < date("Y-m-d")) {
            $output = [
                'success' => 0,
                'msg' => 'خطأ، عملية جرد مغلقة '
            ];
            return redirect()->back()->with('status', $output);
        }
        if ($transaction->end_date < date("Y-m-d")) {
            $output = [
                'success' => 0,
                'msg' => 'خطأ، عملية جرد منتهية  '
            ];
            return redirect()->back()->with('status', $output);
        }
        $transaction_id = $id;
        $business_id = request()->session()->get('user.business_id');
        $variations = Variation::join('products', 'products.id', 'variations.product_id')->where('products.business_id', $business_id)
            ->select(
                'variations.*',
                'products.name as product_name'
            )
            ->get();
        $last_row = Stockingline::where('inventory_transaction_id', $transaction_id)->latest('id')->first();
        if ($last_row) {
            $lastproduct = Variation::join('products', 'products.id', 'variations.product_id')->where('variations.id', $last_row->variation_id)
                ->select(
                    'variations.*',
                    'products.name as product_name'
                )->first();
        } else {
            $lastproduct = new \stdClass();
            $lastproduct->product_name = ''; //[];
        }
        return view('inventory::transaction_form')
            ->with(compact('variations', 'transaction_id', 'lastproduct'));
    }
    public function get_last_product(Request $request)
    {
        $transaction_id = $request->transaction_id;
        $last_row = Stockingline::where('inventory_transaction_id', $transaction_id)->latest('id')->first();
        $lastProductName = Variation::join('products', 'products.id', 'variations.product_id')->where('variations.id', $last_row->variation_id)
            ->select(
                'variations.*',
                'products.name as product_name'
            )->value('product_name');
        return $lastProductName;
    }
    public function transaction_ajax_post(Request $request)
    {
        $business_id = request()->session()->get('user.business_id');
        $transaction_id = $request->transaction_id;
        try {
            DB::beginTransaction();
            $exist = Stockingline::where('inventory_transaction_id', $request->transaction_id)->where('variation_id', $request->variation_id)->first();
            if (! $exist) {
                $stock_line_id = Stockingline::insertGetId([
                    'inventory_transaction_id' => $request->transaction_id,
                    'new_quantity' => $request->new_quantity,
                    'variation_id' => $request->variation_id,
                    'created_by' => auth()->id()
                ]);
            } else {
                $stock_line_id = $exist->id;
                Stockingline::whereId($exist->id)->update([
                    'inventory_transaction_id' => $request->transaction_id,
                    'new_quantity' => $request->new_quantity,
                    'variation_id' => $request->variation_id,
                    'created_by' => auth()->id(),
                    'updated_at' => now(),
                ]);
            }
            $output = [
                'success' => 1,
                'msg' => 'تم اضافة الكمية الفعلية بنجاح'
            ];

            $output = $this->make_liquidation($transaction_id, $stock_line_id);
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
    public function make_liquidation($transaction_id, $stock_lines_id)
    {
        $transaction = Transaction::whereId($transaction_id)->first();
        $location_id = $transaction->location_id;

        $stocktacking_line = Stockingline::whereId($stock_lines_id)->first();
        try {


            $stock = VariationLocationDetails::where('variation_id', $stocktacking_line->variation_id)
                ->where('location_id', $location_id)
                ->first();
            Stockingline::whereId($stocktacking_line->id)->update(['current_stock' => $stock->qty_available]);

            VariationLocationDetails::where('variation_id', $stocktacking_line->variation_id)
                ->where('location_id', $location_id)
                ->update(['qty_available' => $stocktacking_line->new_quantity]);
            $output = [
                'success' => 1,
                'msg' => 'تم الجرد والتسوية'
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

    public function delete_from_stocktacking(Request $request)
    {
        try {
            Stockingline::whereId($request->id)->delete();

            $output = [
                'success' => 1,
                'msg' => 'تم حذف جرد المنتج'
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


    public function not_tacking_report($id)
    {
        $transactions = Transaction::findOrFail($id);

        $business_id = request()->session()->get('user.business_id');

        if (! auth()->user()->can('stocktacking.report')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            $transactions = DB::table('transactions')->where('id', $id)->first();
            $business_id = request()->session()->get('user.business_id');
            $user_id = request()->session()->get('user.id');
            $products = Product::leftJoin('brands', 'products.brand_id', '=', 'brands.id')
                ->join('units', 'products.unit_id', '=', 'units.id')
                ->leftJoin('tax_rates', 'products.tax', '=', 'tax_rates.id')
                ->join('variations as v', 'v.product_id', '=', 'products.id')
                ->leftJoin('variation_location_details as vld', 'vld.variation_id', '=', 'v.id')
                ->where('products.business_id', $business_id)
                ->where('vld.location_id', $transactions->location_id)
                ->where('products.type', '!=', 'modifier')
                //->where('st.transaction_id',$id)
                ->whereRaw("v.id  not in (select variation_id from stocktacking_lines where stocktacking_lines.inventory_transaction_id=$id)")
                ->select(
                    DB::raw("(select transactions.id from transactions where transactions.id =$id) as transaction_id "),
                    'products.name',
                    'v.name as variation_name',
                    'vld.qty_available',
                    'v.id as var_id'
                );
            $current_stock = request()->get('current_stock');

            if ($current_stock == 'zero') {

                $products->where('vld.qty_available', '0');
            }
            if ($current_stock == 'gtzero') {
                $products->where('vld.qty_available', '>', 0);
            }
            if ($current_stock == 'lszero') {
                $products->where('vld.qty_available', '<', 0);
            }

            $category_id = request()->get('category_id', null);
            if (! empty($category_id)) {
                $products->where('products.category_id', $category_id);
            }

            $brand_id = request()->get('brand_id', null);
            if (! empty($brand_id)) {
                $products->where('products.brand_id', $brand_id);
            }
            $products->get();
            foreach ($products as $row) {
                $row->transaction_id = $id;
            }

            return Datatables::of($products)
                ->addColumn(
                    'action',
                    function ($row) {
                        $href = '/stocktacking/transaction/' . $row->transaction_id . '?var_id=' . $row->var_id;
                        $word = "جرد";
                        $html = '<a class="btn btn-info dropdown-toggle btn-xs" target="_blank" href="' . $href . '">' . $word . '</a>';
                        return $html;
                    }
                )
                ->rawColumns(['product_name', 'variation_name', 'action'])
                ->make(true);
        }
        $categories = Category::forDropdown($business_id, 'product');
        $brands = Brands::forDropdown($business_id);
        return view('stocktacking.not_tacking_report', ['transaction_id' => $id, 'categories' => $categories, 'brands' => $brands]);
    }

    public function report_plus($id)
    {
        if (! auth()->user()->can('stocktacking.report')) {
            abort(403, 'Unauthorized action.');
        }

        $location = Transaction::select('business_locations.name')
            ->where('transactions.id', $id)
            ->join('business_locations', 'transactions.location_id', '=', 'business_locations.id')
            ->first();

        if (request()->ajax()) {
            $transactions = Transaction::findOrFail($id);

            $lines = Stockingline::join('transactions', 'stocktacking_lines.inventory_transaction_id', 'transactions.id')
                ->join('variations', 'variations.id', 'stocktacking_lines.variation_id')
                ->join('products', 'products.id', 'variations.product_id')
                ->join('variation_location_details as vld', 'vld.variation_id', 'stocktacking_lines.variation_id')
                ->join('users', 'stocktacking_lines.created_by', 'users.id')
                ->where('transactions.id', $id)
                ->where('stocktacking_lines.new_quantity', '>', DB::raw('stocktacking_lines.current_stock'))
                ->where('vld.location_id', $transactions->location_id)
                ->select(
                    'products.name as product_name',
                    'stocktacking_lines.new_quantity',
                    'stocktacking_lines.current_stock',
                    'variations.name as variation_name',
                    'vld.qty_available',
                    'users.first_name',
                    'users.last_name',
                    'users.id as user_id',
                    'stocktacking_lines.created_at',
                    'stocktacking_lines.updated_at'
                )
                ->get();
            return Datatables::of($lines)
                ->addColumn(
                    'compare',
                    function ($row) {
                        return ((float) $row->new_quantity - (float) $row->current_stock);
                    }
                )
                ->rawColumns(['product_name', 'current_stock', 'new_quantity', 'variation_name', 'qty_available', 'first_name', 'created_at', 'compare'])
                ->make(true);
        }

        return view('stocktacking.plus_report', ['transaction_id' => $id, 'location' => $location]);
    }
    public function report_minus($id)
    {
        if (! auth()->user()->can('stocktacking.report')) {
            abort(403, 'Unauthorized action.');
        }

        $location = Transaction::select('business_locations.name')
            ->where('transactions.id', $id)
            ->join('business_locations', 'transactions.location_id', '=', 'business_locations.id')
            ->first();

        if (request()->ajax()) {
            $transaction = Transaction::findOrFail($id);
            // $business_id = request()->session()->get('user.business_id');
            // $user_id = request()->session()->get('user.id');
            $lines = Stockingline::join('transactions', 'stocktacking_lines.inventory_transaction_id', 'transactions.id')
                ->join('variations', 'variations.id', 'stocktacking_lines.variation_id')
                ->join('products', 'products.id', 'variations.product_id')
                ->join('variation_location_details as vld', 'vld.variation_id', 'stocktacking_lines.variation_id')
                ->join('users', 'stocktacking_lines.created_by', 'users.id')
                ->where('transactions.id', $id)
                ->where('stocktacking_lines.new_quantity', '<', DB::raw('stocktacking_lines.current_stock'))

                ->where('vld.location_id', $transaction->location_id)
                ->select(
                    'products.name as product_name',
                    'stocktacking_lines.new_quantity',
                    'stocktacking_lines.current_stock',
                    'variations.name as variation_name',
                    'vld.qty_available',
                    'users.first_name',
                    'users.last_name',
                    'users.id as user_id',
                    'stocktacking_lines.created_at',
                    'stocktacking_lines.updated_at'
                )->get();
            return Datatables::of($lines)
                ->addColumn(
                    'compare',
                    function ($row) {
                        $compare = (float) $row->new_quantity - (float) $row->current_stock;
                        return $compare;
                    }
                )
                ->rawColumns(['product_name', 'current_stock', 'new_quantity', 'variation_name', 'qty_available', 'first_name', 'created_at', 'compare'])
                ->make(true);
        }

        return view('stocktacking.minus_report', ['transaction_id' => $id, 'location' => $location]);
    }

    public function changeStatus($id, $status)
    {

        if (! auth()->user()->can('stocktacking.changeStatus')) {
            abort(403, 'Unauthorized action.');
        }
        $business_id = request()->session()->get('user.business_id');
        $transaction = Transaction::findOrFail($id);
        if ($status == 'on' && $transaction->end_date < date("Y-m-d")) {
            $output = [
                'success' => 0,
                'msg' => 'خطأ، عملية جرد منتهية '
            ];
            return redirect('stocktacking')->with('status', $output);
        }

        $transaction_on = Transaction::where('business_id', $business_id)
            ->where('status', 'on')
            ->where('location_id', $transaction->location_id)
            ->count();


        if ($transaction_on && $status == 'on') {
            $output = [
                'success' => 0,
                'msg' => 'هناك عملية جرد مفتوحة بالفعل '
            ];
            return redirect('stocktacking')->with('status', $output);
        }
        try {

            Transaction::findOrFail($id)->update(['status' => $status]);
            $output = [
                'success' => 1,
                'msg' => 'تم تحديث حالة الجرد   '
            ];
        } catch (Exception $e) {
            DB::rollBack();
            logger()->emergency("File: {$e->getFile()} Line: {$e->getLine()} Message: {$e->getMessage()}");

            $output = [
                'success' => 0,
                'msg' => __('messages.something_went_wrong')
            ];
        }
    }

    public function Stock_liquidation(Request $request)
    {


        $transaction = Transaction::findOrFail($request->transaction_id);
        if ($transaction->sub_type == 'liquidation') {
            $output = [
                'success' => 0,
                'msg' => __('تمت التصفية من قبل')
            ];
            return $output;
        }
        if ($transaction->end_date < date("Y-m-d")) {
            $output = [
                'success' => 0,
                'msg' => __('  انتهت فترة الجرد ')
            ];
            return $output;
        }
        $transaction->update(['sub_type' => 'liquidation']);
        $location_id = $transaction->location_id;
        $stocktacking_lines = Stockingline::where('inventory_transaction_id', $transaction->id)->get();
        try {
            //update qty available
            DB::beginTransaction();

            foreach ($stocktacking_lines as $stockLine) {
                $stock = VariationLocationDetails::where('variation_id', $stockLine->variation_id)
                    ->where('location_id', $location_id)
                    ->firstOrFail();
                $stockLine->update(['current_stock' => $stock->qty_available]);

                VariationLocationDetails::where('variation_id', $stockLine->variation_id)
                    ->where('location_id', $location_id)
                    ->update(['qty_available' => $stockLine->new_quantity]);
            }

            $output = [
                'success' => 1,
                'msg' => 'تم تصفية الجرد بنجاح'
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
}
