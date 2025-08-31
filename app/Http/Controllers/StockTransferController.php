<?php

namespace App\Http\Controllers;

use Exception;

use Datatables;
use App\Transaction;
use App\PurchaseLine;
use App\BusinessLocation;
use App\Store;
use App\StoreDetail;
use App\Utils\ModuleUtil;
use App\Utils\ProductUtil;
use Illuminate\Http\Request;

use App\Utils\TransactionUtil;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;
use App\TransactionSellLinesPurchaseLines;

class StockTransferController extends Controller
{

    /**
     * All Utils instance.
     *
     */
    protected $productUtil;
    protected $transactionUtil;
    protected $moduleUtil;
    protected $status_colors;
    protected $stock_transfers;
    protected $business_id;
    protected $edit_days;
    protected $statuses;

    /**
     * Constructor
     *
     * @param ProductUtils $product
     * @return void
     */
    public function __construct(
        ProductUtil $productUtil,
        TransactionUtil $transactionUtil,
        ModuleUtil $moduleUtil
    ) {
        $this->productUtil = $productUtil;
        $this->transactionUtil = $transactionUtil;
        $this->moduleUtil = $moduleUtil;
        $this->status_colors = [
            'in_transit' => 'bg-yellow',
            'completed' => 'bg-green',
            'pending' => 'bg-red',
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!auth()->user()->can('purchase.view') && !auth()->user()->can('purchase.create')) {
            abort(403, 'Unauthorized action.');
        }

        $this->statuses = $this->stockTransferStatuses();

        if (request()->ajax()) {
            $this->business_id = request()->session()->get('user.business_id');

            request()->session()->get('business.transaction_edit_days');

            $this->get_stock_transfers();

            return $this->get_Datatables();
        }

        $statuses = $this->statuses;
        // dd($request->menuItems);
        $menuItems = $request->menuItems;
        return view('stock_transfer.index')->with(compact('statuses', 'menuItems'));
    }

    public function get_stock_transfers()
    {

        $this->stock_transfers = Transaction::join(
            'stores as s1',
            'transactions.store_id',
            '=',
            's1.id'
        )->join(
            'business_locations AS l1',
            'transactions.location_id',
            '=',
            'l1.id'
        )

            ->join('transactions as t2', 't2.transfer_parent_id', '=', 'transactions.id')
            ->join(
                'stores AS s2',
                't2.store_id',
                '=',
                's2.id'
            )
            ->join(
                'business_locations AS l2',
                't2.location_id',
                '=',
                'l2.id'
            )
            ->where('transactions.business_id', $this->business_id)
            ->where('transactions.type', 'sell_transfer')
            ->select(
                'transactions.id',
                'transactions.transaction_date',
                'transactions.ref_no',
                'l1.name as location_from',
                'l2.name as location_to',
                's1.name as store_from',
                's2.name as store_to',
                'transactions.final_total',
                'transactions.shipping_charges',
                'transactions.additional_notes',
                'transactions.id as DT_RowId',
                'transactions.status'
            );
    }

    public function get_Datatables()
    {



        return Datatables::of($this->stock_transfers)
            ->addColumn('action', function ($row) {
                $html = '<button type="button" title="' . __("stock_adjustment.view_details") . '" class="btn btn-primary btn-xs btn-modal" data-container=".view_modal" data-href="' . action('StockTransferController@show', [$row->id]) . '"><i class="fa fa-eye" aria-hidden="true"></i> ' . __('messages.view') . '</button>';

                $html .= ' <a href="#" class="print-invoice btn btn-info btn-xs" data-href="' . action('StockTransferController@printInvoice', [$row->id]) . '"><i class="fa fa-print" aria-hidden="true"></i> ' . __("messages.print") . '</a>';

                //muhib add (int) in this line
                $date = \Carbon::parse($row->transaction_date)->addDays((int) $this->edit_days);
                $today = today();

                if ($date->gte($today)) {
                    $html .= '&nbsp;
                <button type="button" data-href="' . action("StockTransferController@destroy", [$row->id]) . '" class="btn btn-danger btn-xs delete_stock_transfer"><i class="fa fa-trash" aria-hidden="true"></i> ' . __("messages.delete") . '</button>';
                }

                if ($row->status != 'final') {
                    $html .= '&nbsp;
                <a href="' . action("StockTransferController@edit", [$row->id]) . '" class="btn btn-primary btn-xs"><i class="fa fa-edit" aria-hidden="true"></i> ' . __("messages.edit") . '</a>';
                }

                return $html;
            })
            ->editColumn(
                'final_total',
                '<span class="display_currency" data-currency_symbol="true">{{$final_total}}</span>'
            )
            ->editColumn(
                'shipping_charges',
                '<span class="display_currency" data-currency_symbol="true">{{$shipping_charges}}</span>'
            )
            ->editColumn('status', function ($row) {
                $row->status = $row->status == 'final' ? 'completed' : $row->status;
                $status = $this->statuses[$row->status];
                $status_color = !empty($this->status_colors[$row->status]) ? $this->status_colors[$row->status] : 'bg-gray';
                $status = $row->status != 'completed' ? '<a href="#" class="stock_transfer_status" data-status="' . $row->status . '" data-href="' . action("StockTransferController@updateStatus", [$row->id]) . '"><span class="label ' . $status_color . '">' . $this->statuses[$row->status] . '</span></a>' : '<span class="label ' . $status_color . '">' . $this->statuses[$row->status] . '</span>';

                return $status;
            })
            ->editColumn('transaction_date', '{{@format_datetime($transaction_date)}}')
            ->rawColumns(['final_total', 'action', 'shipping_charges', 'status'])
            ->setRowAttr([
                'data-href' => function ($row) {
                    return action('StockTransferController@show', [$row->id]);
                }
            ])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (!auth()->user()->can('purchase.create')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');

        //Check if subscribed or not
        if (!$this->moduleUtil->isSubscribed($business_id)) {
            return $this->moduleUtil->expiredResponse(action('StockTransferController@index'));
        }

        $business_locations = BusinessLocation::forDropdown($business_id);

        $statuses = $this->stockTransferStatuses();
        $menuItems = $request->menuItems;
        return view('stock_transfer.create')
            ->with(compact(
                'business_locations',
                'statuses',
                'menuItems'
            ));
    }


    public function getStoresByLocations(Request $request)
    {
        $locationIds = $request->input('location_ids');
        // Fetch stores for these locations. Adjust logic as needed.
        $stores = Store::where('stores.location_id', $locationIds)
            ->get();

        $stores = $stores->pluck('name', 'id');


        return response()->json($stores);
    }

    private function stockTransferStatuses()
    {
        return [
            'pending' => __('lang_v1.pending'),
            'in_transit' => __('lang_v1.in_transit'),
            'completed' => __('restaurant.completed')
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('purchase.create')) {
            abort(403, 'Unauthorized action.');
        }

        // dd($request->all());
        try {
            $business_id = $request->session()->get('user.business_id');

            //Check if subscribed or not
            if (!$this->moduleUtil->isSubscribed($business_id)) {

                return $this->moduleUtil->expiredResponse(action('StockTransferController@index'));
            }

            DB::beginTransaction();

            $input_data = $request->only([
                'location_id',
                'store_id',
                'ref_no',
                'transaction_date',
                'additional_notes',
                'shipping_charges',
                'final_total'
            ]);
            $status = $request->input('status');
            $user_id = $request->session()->get('user.id');

            $input_data['final_total'] = $this->productUtil->num_uf($input_data['final_total']);
            $input_data['total_before_tax'] = $input_data['final_total'];
            $input_data['type'] = 'sell_transfer';
            $input_data['business_id'] = $business_id;
            $input_data['created_by'] = $user_id;
            $input_data['transaction_date'] = $this->productUtil->uf_date($input_data['transaction_date'], true);
            $input_data['shipping_charges'] = $this->productUtil->num_uf($input_data['shipping_charges']);
            $input_data['payment_status'] = 'paid';
            $input_data['status'] = $status == 'completed' ? 'final' : $status;
            // ------add this muhib---
            $input_data['essentials_duration'] = 0;
            // ----------
            //Update reference count
            $ref_count = $this->productUtil->setAndGetReferenceCount('stock_transfer');
            //Generate reference number
            if (empty($input_data['ref_no'])) {
                $input_data['ref_no'] = $this->productUtil->generateReferenceNumber('stock_transfer', $ref_count);
            }

            $products = $request->input('products');
            $sell_lines = [];
            $purchase_lines = [];


            // ---------------------------------------muhib add this for transfer from store into store not branch-------------------------------------------



            // if ($request->input('transfer_location_id') == $request->input('location_id')) {

            //     if ($request->input('transfer_store_id') != $request->input('store_id')) {

            //         // -----------------------------------Decrease product stock from sell location And increase product stock at purchase location----------------------------------------------------------------------------------------
            //         if ($status == 'completed') {

            //             foreach ($products as $product) {

            //                 if ($product['enable_stock']) {

            //                     $this->productUtil->decreaseProductQuantity(
            //                         $product['product_id'],
            //                         $product['variation_id'],
            //                         $request->input('location_id'),
            //                         $request->input('store_id'),
            //                         $this->productUtil->num_uf($product['quantity'])
            //                     );

            //                     $this->productUtil->updateProductQuantity(
            //                         $request->input('transfer_location_id'),
            //                         $request->input('transfer_store_id'),
            //                         $product['product_id'],
            //                         $product['variation_id'],
            //                         $product['quantity']
            //                     );
            //                 }
            //             }


            //             $business = [
            //                 'id' => $business_id,
            //                 'accounting_method' => $request->session()->get('business.accounting_method'),
            //                 'location_id' => $request->input('location_id'),
            //                 'store_id' => $request->input('store_id')
            //             ];
            //         }
            //         $output = [
            //             'success' => 1,
            //             'msg' => __('lang_v1.stock_transfer_added_successfully')
            //         ];
            //     } else {

            //         $output = [
            //             'success' => 1,
            //             'msg' => __('lang_v1.stock_transfer_not_added')
            //         ];
            //     }
            // } else {

            if (!empty($products)) {

                foreach ($products as $product) {
                    $sell_line_arr = [
                        'product_id' => $product['product_id'],
                        'variation_id' => $product['variation_id'],
                        'quantity' => $this->productUtil->num_uf($product['quantity']),
                        'item_tax' => 0,
                        'tax_id' => null
                    ];

                    $purchase_line_arr = $sell_line_arr;
                    $sell_line_arr['unit_price'] = $this->productUtil->num_uf($product['unit_price']);
                    $sell_line_arr['unit_price_inc_tax'] = $sell_line_arr['unit_price'];
                    $purchase_line_arr['purchase_price'] = $sell_line_arr['unit_price'];
                    $purchase_line_arr['purchase_price_inc_tax'] = $sell_line_arr['unit_price'];


                    if (!empty($product['lot_no_line_id'])) {
                        //Add lot_no_line_id to sell line
                        $sell_line_arr['lot_no_line_id'] = $product['lot_no_line_id'];

                        //Copy lot number and expiry date to purchase line
                        $lot_details = PurchaseLine::find($product['lot_no_line_id']);
                        $purchase_line_arr['lot_number'] = $lot_details->lot_number;
                        $purchase_line_arr['mfg_date'] = $lot_details->mfg_date;
                        $purchase_line_arr['exp_date'] = $lot_details->exp_date;
                    }

                    $sell_lines[] = $sell_line_arr;
                    $purchase_lines[] = $purchase_line_arr;
                }
            }


            // -------------------------------Create Sell Transfer transaction--------------------------------------------------------------------------------------------

            $sell_transfer = Transaction::create($input_data);
            // --------------------------------Create Purchase Transfer at transfer location-------------------------------------------------------------------------------------------
            $input_data['type'] = 'purchase_transfer';
            $input_data['location_id'] = $request->input('transfer_location_id');
            $input_data['transfer_store_id'] = $request->input('transfer_store_id');
            $input_data['transfer_parent_id'] = $sell_transfer->id;
            $input_data['status'] = $status == 'completed' ? 'received' : $status;
            // ------add this muhib---
            $input_data['essentials_duration'] = 0;
            // ----------
            $purchase_transfer = Transaction::create($input_data);
            // --------------------------------Sell Product from first location-------------------------------------------------------------------------------------------
            if (!empty($sell_lines)) {
                $this->transactionUtil->createOrUpdateSellLines(
                    $sell_transfer,
                    $sell_lines,
                    $input_data['location_id'],
                    $input_data['store_id']
                );
            }
            // ----------------------------------Purchase product in second location-----------------------------------------------------------------------------------------

            if (!empty($purchase_lines)) {

                $purchase_transfer->purchase_lines()
                    ->createMany($purchase_lines);
            }

            // -----------------------------------Decrease product stock from sell location And increase product stock at purchase location----------------------------------------------------------------------------------------
            if ($status == 'completed') {
                foreach ($products as $product) {
                    if ($product['enable_stock']) {
                        $this->productUtil->decreaseProductQuantity(
                            $product['product_id'],
                            $product['variation_id'],
                            $sell_transfer->location_id,
                            $sell_transfer->store_id,
                            $this->productUtil->num_uf($product['quantity'])
                        );

                        $this->productUtil->updateProductQuantity(
                            $purchase_transfer->location_id,
                            $purchase_transfer->store_id,
                            $product['product_id'],
                            $product['variation_id'],
                            $product['quantity']
                        );
                    }
                }
                // ----------------------------------Adjust stock over selling if found-----------------------------------------------------------------------------------------
                $this->productUtil->adjustStockOverSelling($purchase_transfer);
                // -----------------------------------Map sell lines with purchase lines----------------------------------------------------------------------------------------
                $business = [
                    'id' => $business_id,
                    'accounting_method' => $request->session()->get('business.accounting_method'),
                    'location_id' => $sell_transfer->location_id,
                    'store_id' => $sell_transfer->store_id
                ];
                $this->transactionUtil->mapPurchaseSell(
                    $business,
                    $sell_transfer->sell_lines,
                    'purchase'
                );
            }
            // -------------------------------------------------------------
            $this->transactionUtil->activityLog($sell_transfer, 'added');


            $output = [
                'success' => 1,
                'msg' => __('lang_v1.stock_transfer_added_successfully')
            ];
            // }




            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            logger()->emergency("File: {$e->getFile()} Line: {$e->getLine()} Message: {$e->getMessage()}");

            $output = [
                'success' => 0,
                'msg' => $e->getMessage()
            ];
        }

        return redirect('stock-transfers')->with('status', $output);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        if (!auth()->user()->can('purchase.view')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');

        $sell_transfer = Transaction::where('business_id', $business_id)
            ->where('id', $id)
            ->where('type', 'sell_transfer')
            ->with(
                'contact',
                'sell_lines',
                'sell_lines.product',
                'sell_lines.variations',
                'sell_lines.variations.product_variation',
                'sell_lines.lot_details',
                'location',
                'sell_lines.product.unit'
            )
            ->first();

        $purchase_transfer = Transaction::where('business_id', $business_id)
            ->where('transfer_parent_id', $sell_transfer->id)
            ->where('type', 'purchase_transfer')
            ->first();

        $location_details = ['sell' => $sell_transfer->location, 'purchase' => $purchase_transfer->location];

        $lot_n_exp_enabled = false;
        if (request()->session()->get('business.enable_lot_number') == 1 || request()->session()->get('business.enable_product_expiry') == 1) {
            $lot_n_exp_enabled = true;
        }

        $statuses = $this->stockTransferStatuses();

        $statuses['final'] = __('restaurant.completed');


        $activities = Activity::forSubject($sell_transfer)
            ->with(['causer', 'subject'])
            ->latest()
            ->get();




        $menuItems = $request->menuItems;

        return view('stock_transfer.show')
            ->with(compact(
                'sell_transfer',
                'location_details',
                'lot_n_exp_enabled',
                'statuses',
                'activities',
                'menuItems'
            ));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response|array
     */

    public function destroy($id)
    {
        if (!auth()->user()->can('purchase.delete')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            if (request()->ajax()) {
                $edit_days = request()->session()->get('business.transaction_edit_days');
                if (!$this->transactionUtil->canBeEdited($id, $edit_days)) {
                    return [
                        'success' => 0,
                        'msg' => __('messages.transaction_edit_not_allowed', ['days' => $edit_days])
                    ];
                }

                //Get sell transfer transaction
                $sell_transfer = Transaction::where('id', $id)
                    ->where('type', 'sell_transfer')
                    ->with(['sell_lines'])
                    ->first();

                //Get purchase transfer transaction
                $purchase_transfer = Transaction::where('transfer_parent_id', $sell_transfer->id)
                    ->where('type', 'purchase_transfer')
                    ->with(['purchase_lines'])
                    ->first();

                //Check if any transfer stock is deleted and delete purchase lines
                $purchase_lines = $purchase_transfer->purchase_lines;
                foreach ($purchase_lines as $purchase_line) {
                    if ($purchase_line->quantity_sold > 0) {
                        return [
                            'success' => 0,
                            'msg' => __('lang_v1.stock_transfer_cannot_be_deleted')
                        ];
                    }
                }

                DB::beginTransaction();
                //Get purchase lines from transaction_sell_lines_purchase_lines and decrease quantity_sold
                $sell_lines = $sell_transfer->sell_lines;
                $deleted_sell_purchase_ids = [];
                $products = []; //variation_id as array

                foreach ($sell_lines as $sell_line) {
                    $purchase_sell_line = TransactionSellLinesPurchaseLines::where('sell_line_id', $sell_line->id)->first();

                    if (!empty($purchase_sell_line)) {
                        //Decrease quntity sold from purchase line
                        PurchaseLine::where('id', $purchase_sell_line->purchase_line_id)
                            ->decrement('quantity_sold', $sell_line->quantity);

                        $deleted_sell_purchase_ids[] = $purchase_sell_line->id;

                        //variation details
                        if (isset($products[$sell_line->variation_id])) {
                            $products[$sell_line->variation_id]['quantity'] += $sell_line->quantity;
                            $products[$sell_line->variation_id]['product_id'] = $sell_line->product_id;
                        } else {
                            $products[$sell_line->variation_id]['quantity'] = $sell_line->quantity;
                            $products[$sell_line->variation_id]['product_id'] = $sell_line->product_id;
                        }
                    }
                }

                //Update quantity available in both location
                if (!empty($products)) {
                    foreach ($products as $key => $value) {
                        //Decrease from location 2
                        $this->productUtil->decreaseProductQuantity(
                            $products[$key]['product_id'],
                            $key,
                            $purchase_transfer->location_id,
                            $products[$key]['quantity']
                        );

                        //Increase in location 1
                        $this->productUtil->updateProductQuantity(
                            $sell_transfer->location_id,
                            $products[$key]['product_id'],
                            $key,
                            $products[$key]['quantity']
                        );
                    }
                }

                //Delete sale line purchase line
                if (!empty($deleted_sell_purchase_ids)) {
                    TransactionSellLinesPurchaseLines::whereIn('id', $deleted_sell_purchase_ids)
                        ->delete();
                }

                //Delete both transactions
                $sell_transfer->delete();
                $purchase_transfer->delete();

                $output = [
                    'success' => 1,
                    'msg' => __('lang_v1.stock_transfer_delete_success')
                ];
                DB::commit();
            }
        } catch (Exception $e) {
            DB::rollBack();
            logger()->emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

            $output = [
                'success' => 0,
                'msg' => __('messages.something_went_wrong')
            ];
        }
        return $output;
    }

    /**
     * Checks if ref_number and supplier combination already exists.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function printInvoice($id)
    {
        try {
            $business_id = request()->session()->get('user.business_id');

            $sell_transfer = Transaction::where('business_id', $business_id)
                ->where('id', $id)
                ->where('type', 'sell_transfer')
                ->with(
                    'contact',
                    'sell_lines',
                    'sell_lines.product',
                    'sell_lines.variations',
                    'sell_lines.variations.product_variation',
                    'sell_lines.lot_details',
                    'location',
                    // 'store',
                    'sell_lines.product.unit'
                )
                ->first();

            $purchase_transfer = Transaction::where('business_id', $business_id)
                ->where('transfer_parent_id', $sell_transfer->id)
                ->where('type', 'purchase_transfer')
                ->first();

            $location_details = ['sell' => $sell_transfer->location, 'purchase' => $purchase_transfer->location];

            $lot_n_exp_enabled = false;
            if (request()->session()->get('business.enable_lot_number') == 1 || request()->session()->get('business.enable_product_expiry') == 1) {
                $lot_n_exp_enabled = true;
            }


            $output = ['success' => 1, 'receipt' => []];
            $output['receipt']['html_content'] = view('stock_transfer.print', compact(
                'sell_transfer',
                'location_details',
                'lot_n_exp_enabled'
            ))->render();
        } catch (Exception $e) {
            logger()->emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

            $output = [
                'success' => 0,
                'msg' => __('messages.something_went_wrong')
            ];
        }

        return $output;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $business_id = request()->session()->get('user.business_id');

        $business_locations = BusinessLocation::forDropdown($business_id);

        $statuses = $this->stockTransferStatuses();

        $sell_transfer = Transaction::where('business_id', $business_id)
            ->where('type', 'sell_transfer')
            ->where('status', '!=', 'final')
            ->with(['sell_lines'])
            ->findOrFail($id);

        $purchase_transfer = Transaction::where(
            'business_id',
            $business_id
        )
            ->where('transfer_parent_id', $id)
            ->where('status', '!=', 'received')
            ->where('type', 'purchase_transfer')
            ->first();

        $products = [];
        foreach ($sell_transfer->sell_lines as $sell_line) {
            $product = $this->productUtil->getDetailsFromVariation($sell_line->variation_id, $business_id, $sell_transfer->location_id);
            $product->formatted_qty_available = $this->productUtil->num_f($product->qty_available);
            $product->quantity_ordered = $sell_line->quantity;
            $product->transaction_sell_lines_id = $sell_line->id;
            $product->lot_no_line_id = $sell_line->lot_no_line_id;

            //Get lot number dropdown if enabled
            $lot_numbers = [];
            if (request()->session()->get('business.enable_lot_number') == 1 || request()->session()->get('business.enable_product_expiry') == 1) {
                $lot_number_obj = $this->transactionUtil->getLotNumbersFromVariation($sell_line->variation_id, $business_id, $sell_transfer->location_id, true);
                foreach ($lot_number_obj as $lot_number) {
                    $lot_number->qty_formated = $this->productUtil->num_f($lot_number->qty_available);
                    $lot_numbers[] = $lot_number;
                }
            }
            $product->lot_numbers = $lot_numbers;

            $products[] = $product;
        }
        $menuItems = $request->menuItems;

        return view('stock_transfer.edit')
            ->with(compact('sell_transfer', 'purchase_transfer', 'business_locations', 'statuses', 'products', 'menuItems'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('purchase.create')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $business_id = $request->session()->get('user.business_id');

            //Check if subscribed or not
            if (!$this->moduleUtil->isSubscribed($business_id)) {
                return $this->moduleUtil->expiredResponse(action('StockTransferController@index'));
            }

            $business_id = request()->session()->get('user.business_id');

            $sell_transfer = Transaction::where('business_id', $business_id)
                ->where('type', 'sell_transfer')
                ->findOrFail($id);

            $sell_transfer_before = $sell_transfer->replicate();

            $purchase_transfer = Transaction::where(
                'business_id',
                $business_id
            )
                ->where('transfer_parent_id', $id)
                ->where('type', 'purchase_transfer')
                ->with(['purchase_lines'])
                ->first();

            $status = $request->input('status');

            DB::beginTransaction();

            $input_data = $request->only(['transaction_date', 'additional_notes', 'shipping_charges', 'final_total']);
            $status = $request->input('status');

            $input_data['final_total'] = $this->productUtil->num_uf($input_data['final_total']);
            $input_data['total_before_tax'] = $input_data['final_total'];

            $input_data['transaction_date'] = $this->productUtil->uf_date($input_data['transaction_date'], true);
            $input_data['shipping_charges'] = $this->productUtil->num_uf($input_data['shipping_charges']);
            $input_data['status'] = $status == 'completed' ? 'final' : $status;

            $products = $request->input('products');
            $sell_lines = [];
            $purchase_lines = [];
            $edited_purchase_lines = [];
            if (!empty($products)) {
                foreach ($products as $product) {
                    $sell_line_arr = [
                        'product_id' => $product['product_id'],
                        'variation_id' => $product['variation_id'],
                        'quantity' => $this->productUtil->num_uf($product['quantity']),
                        'item_tax' => 0,
                        'tax_id' => null
                    ];

                    $purchase_line_arr = $sell_line_arr;
                    $sell_line_arr['unit_price'] = $this->productUtil->num_uf($product['unit_price']);
                    $sell_line_arr['unit_price_inc_tax'] = $sell_line_arr['unit_price'];

                    $purchase_line_arr['purchase_price'] = $sell_line_arr['unit_price'];
                    $purchase_line_arr['purchase_price_inc_tax'] = $sell_line_arr['unit_price'];
                    if (isset($product['transaction_sell_lines_id'])) {
                        $sell_line_arr['transaction_sell_lines_id'] = $product['transaction_sell_lines_id'];
                    }

                    if (!empty($product['lot_no_line_id'])) {
                        //Add lot_no_line_id to sell line
                        $sell_line_arr['lot_no_line_id'] = $product['lot_no_line_id'];

                        //Copy lot number and expiry date to purchase line
                        $lot_details = PurchaseLine::find($product['lot_no_line_id']);
                        $purchase_line_arr['lot_number'] = $lot_details->lot_number;
                        $purchase_line_arr['mfg_date'] = $lot_details->mfg_date;
                        $purchase_line_arr['exp_date'] = $lot_details->exp_date;
                    }

                    $sell_lines[] = $sell_line_arr;

                    $purchase_line = [];
                    //check if purchase_line for the variation exists else create new 
                    foreach ($purchase_transfer->purchase_lines as $pl) {
                        if ($pl->variation_id == $purchase_line_arr['variation_id']) {
                            $pl->update($purchase_line_arr);
                            $edited_purchase_lines[] = $pl->id;
                            $purchase_line = $pl;
                            break;
                        }
                    }
                    if (empty($purchase_line)) {
                        $purchase_line = new PurchaseLine($purchase_line_arr);
                    }

                    $purchase_lines[] = $purchase_line;
                }
            }

            //Create Sell Transfer transaction
            $sell_transfer->update($input_data);
            $sell_transfer->save();

            //Create Purchase Transfer at transfer location
            $input_data['status'] = $status == 'completed' ? 'received' : $status;

            $purchase_transfer->update($input_data);
            $purchase_transfer->save();

            //Sell Product from first location
            if (!empty($sell_lines)) {
                $this->transactionUtil->createOrUpdateSellLines($sell_transfer, $sell_lines, $sell_transfer->location_id, false, 'draft');
            }

            //Purchase product in second location
            if (!empty($purchase_lines)) {
                if (!empty($edited_purchase_lines)) {
                    PurchaseLine::where('transaction_id', $purchase_transfer->id)
                        ->whereNotIn('id', $edited_purchase_lines)
                        ->delete();
                }
                $purchase_transfer->purchase_lines()->saveMany($purchase_lines);
            }

            //Decrease product stock from sell location
            //And increase product stock at purchase location
            if ($status == 'completed') {
                foreach ($products as $product) {
                    if ($product['enable_stock']) {
                        $this->productUtil->decreaseProductQuantity(
                            $product['product_id'],
                            $product['variation_id'],
                            $sell_transfer->location_id,
                            $this->productUtil->num_uf($product['quantity'])
                        );

                        $this->productUtil->updateProductQuantity(
                            $purchase_transfer->location_id,
                            $product['product_id'],
                            $product['variation_id'],
                            $product['quantity']
                        );
                    }
                }

                //Adjust stock over selling if found
                $this->productUtil->adjustStockOverSelling($purchase_transfer);

                //Map sell lines with purchase lines
                $business = [
                    'id' => $business_id,
                    'accounting_method' => $request->session()->get('business.accounting_method'),
                    'location_id' => $sell_transfer->location_id
                ];
                $this->transactionUtil->mapPurchaseSell($business, $sell_transfer->sell_lines, 'purchase');
            }

            $this->transactionUtil->activityLog($sell_transfer, 'edited', $sell_transfer_before);

            $output = [
                'success' => 1,
                'msg' => __('lang_v1.updated_succesfully')
            ];

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

            $output = [
                'success' => 0,
                'msg' => $e->getMessage()
            ];
        }

        return redirect('stock-transfers')->with('status', $output);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, $id)
    {
        if (!auth()->user()->can('purchase.update')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $business_id = request()->session()->get('user.business_id');

            $sell_transfer = Transaction::where('business_id', $business_id)
                ->where('type', 'sell_transfer')
                ->with(['sell_lines', 'sell_lines.product'])
                ->findOrFail($id);

            $purchase_transfer = Transaction::where(
                'business_id',
                $business_id
            )
                ->where('transfer_parent_id', $id)
                ->where('type', 'purchase_transfer')
                ->with(['purchase_lines'])
                ->first();

            $status = $request->input('status');

            DB::beginTransaction();
            if ($status == 'completed' && $sell_transfer->status != 'completed') {

                foreach ($sell_transfer->sell_lines as $sell_line) {
                    if ($sell_line->product->enable_stock) {
                        $this->productUtil->decreaseProductQuantity(
                            $sell_line->product_id,
                            $sell_line->variation_id,
                            $sell_transfer->location_id,
                            $sell_line->quantity
                        );

                        $this->productUtil->updateProductQuantity(
                            $purchase_transfer->location_id,
                            $sell_line->product_id,
                            $sell_line->variation_id,
                            $sell_line->quantity,
                            0,
                            null,
                            false
                        );
                    }
                }

                //Adjust stock over selling if found
                $this->productUtil->adjustStockOverSelling($purchase_transfer);

                //Map sell lines with purchase lines
                $business = [
                    'id' => $business_id,
                    'accounting_method' => $request->session()->get('business.accounting_method'),
                    'location_id' => $sell_transfer->location_id
                ];
                $this->transactionUtil->mapPurchaseSell($business, $sell_transfer->sell_lines, 'purchase');
            }
            $purchase_transfer->status = $status == 'completed' ? 'received' : $status;
            $purchase_transfer->save();
            $sell_transfer->status = $status == 'completed' ? 'final' : $status;
            $sell_transfer->save();

            DB::commit();

            $output = [
                'success' => 1,
                'msg' => __('lang_v1.updated_succesfully')
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

            $output = [
                'success' => 0,
                'msg' => "File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage()
            ];
        }

        return $output;
    }
}
