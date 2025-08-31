<?php

namespace App\Http\Controllers;

use App\AccountTransaction;
use App\PurchaseLine;
use App\Transaction;
use App\Utils\ProductUtil;
use App\Utils\TransactionUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use App\BusinessLocation;
use App\Services\JournalEntries\PurchaseAccountingService;
use App\Store;
use App\StoreDetail;
use Spatie\Activitylog\Models\Activity;

class PurchaseReturnController extends Controller
{
    /**
     * All Utils instance.
     *
     */
    protected $transactionUtil;
    protected $productUtil;
    protected $purchases_returns;
    protected $business_id;
    private PurchaseAccountingService $purchaseAccountingService;

    /**
     * Constructor
     *
     * @param TransactionUtil $transactionUtil
     * @return void
     */
    public function __construct(TransactionUtil $transactionUtil, ProductUtil $productUtil)
    {
        $this->transactionUtil = $transactionUtil;
        $this->productUtil = $productUtil;
        $this->purchaseAccountingService = app(PurchaseAccountingService::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (! auth()->user()->can('purchase_return.view') && ! auth()->user()->can('purchase_return.create')) {
            abort(403, 'Unauthorized action.');
        }

        $this->business_id = request()->session()->get('user.business_id');

        if (request()->ajax()) {

            $this->get_purchases();

            $this->filter();

            return $this->get_datatable();
        }


        $business_locations = BusinessLocation::forDropdown($this->business_id);
        $menuItems = $request->menuItems;
        return view('purchase_return.index')->with(compact(
            'business_locations',
            'menuItems',
        ));
    }



    public function get_datatable()
    {



        return Datatables::of($this->purchases_returns)
            ->addColumn('action', function ($row) {
                $html = '<div class="btn-group">
                            <button type="button" class="btn btn-info dropdown-toggle btn-xs" 
                                data-toggle="dropdown" aria-expanded="false">' .
                    __("messages.actions") .
                    '<span class="caret"></span><span class="sr-only">Toggle Dropdown
                                </span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right" role="menu">';
                if (! empty($row->return_parent_id)) {
                    $html .= '<li><a href="' . action('PurchaseReturnController@add', $row->return_parent_id) . '" ><i class="glyphicon glyphicon-edit"></i>' .
                        __("messages.edit") .
                        '</a></li>';
                } else {
                    $html .= '<li><a href="' . action('CombinedPurchaseReturnController@edit', $row->id) . '" ><i class="glyphicon glyphicon-edit"></i>' .
                        __("messages.edit") .
                        '</a></li>';
                }

                if ($row->payment_status != "paid") {
                    $html .= '<li><a href="' . action('TransactionPaymentController@addPayment', [$row->id]) . '" class="add_payment_modal"><i class="fas fa-money-bill-alt"></i>' . __("purchase.add_payment") . '</a></li>';
                }

                $html .= '<li><a href="' . action('TransactionPaymentController@show', [$row->id]) . '" class="view_payment_modal"><i class="fas fa-money-bill-alt"></i>' . __("purchase.view_payments") . '</a></li>';

                $html .= '<li><a href="' . action('PurchaseReturnController@destroy', $row->id) . '" class="delete_purchase_return" ><i class="fa fa-trash"></i>' .
                    __("messages.delete") .
                    '</a></li>';
                $html .= '</ul></div>';

                return $html;
            })
            ->removeColumn('id')
            ->removeColumn('return_parent_id')
            ->editColumn(
                'final_total',
                '<span class="display_currency final_total" data-currency_symbol="true" data-orig-value="{{$final_total}}">{{$final_total}}</span>'
            )
            ->editColumn('transaction_date', '{{@format_datetime($transaction_date)}}')
            ->editColumn(
                'payment_status',
                '<a href="{{ action("TransactionPaymentController@show", [$id])}}" class="view_payment_modal payment-status payment-status-label" data-orig-value="{{$payment_status}}" data-status-name="@if($payment_status != "paid"){{__(\'lang_v1.\' . $payment_status)}}@else{{__("lang_v1.received")}}@endif"><span class="label @payment_status($payment_status)">@if($payment_status != "paid"){{__(\'lang_v1.\' . $payment_status)}} @else {{__("lang_v1.received")}} @endif
                </span></a>'
            )
            ->editColumn('parent_purchase', function ($row) {
                $html = '';
                if (! empty($row->parent_purchase)) {
                    $html = '<a href="#" data-href="' . action('PurchaseController@show', [$row->return_parent_id]) . '" class="btn-modal" data-container=".view_modal">' . $row->parent_purchase . '</a>';
                }
                return $html;
            })
            ->addColumn('payment_due', function ($row) {
                $due = $row->final_total - $row->amount_paid;
                return '<span class="display_currency payment_due" data-currency_symbol="true" data-orig-value="' . $due . '">' . $due . '</sapn>';
            })
            ->setRowAttr([
                'data-href' => function ($row) {
                    if (auth()->user()->can("purchase.view")) {
                        $return_id = ! empty($row->return_parent_id) ? $row->return_parent_id : $row->id;
                        return action('PurchaseReturnController@show', [$return_id]);
                    } else {
                        return '';
                    }
                }
            ])
            ->rawColumns(['final_total', 'action', 'payment_status', 'parent_purchase', 'payment_due'])
            ->make(true);
    }
    public function get_purchases()
    {


        $this->purchases_returns = Transaction::join(
            'stores',
            'transactions.store_id',
            '=',
            'stores.id'
        )->leftJoin('contacts', 'transactions.contact_id', '=', 'contacts.id')

            ->join(
                'business_locations AS BS',
                'transactions.location_id',
                '=',
                'BS.id'
            )
            ->leftJoin(
                'transactions AS T',
                'transactions.return_parent_id',
                '=',
                'T.id'
            )
            ->leftJoin(
                'transaction_payments AS TP',
                'transactions.id',
                '=',
                'TP.transaction_id'
            )
            ->where('transactions.business_id', $this->business_id)
            ->where('transactions.type', 'purchase_return')
            ->select(
                'transactions.id',
                'transactions.transaction_date',
                'transactions.ref_no',
                'contacts.name',
                'transactions.status',
                'transactions.payment_status',
                'transactions.final_total',
                'transactions.return_parent_id',
                'BS.name as location_name',
                'stores.name as store_name',
                'T.ref_no as parent_purchase',
                DB::raw('SUM(TP.amount) as amount_paid')
            );
    }
    public function filter()
    {


        $permitted_locations = auth()->user()->permitted_locations();
        if ($permitted_locations != 'all') {
            $this->purchases_returns->whereIn('transactions.location_id', $permitted_locations);
        }

        if (! empty(request()->location_id)) {
            $this->purchases_returns->where('transactions.location_id', request()->location_id);
        }
        if (! empty(request()->store_id)) {
            $this->purchases_returns->where('transactions.store_id', request()->store_id);
        }

        if (! empty(request()->supplier_id)) {
            $supplier_id = request()->supplier_id;
            $this->purchases_returns->where('contacts.id', $supplier_id);
        }
        if (! empty(request()->start_date) && ! empty(request()->end_date)) {
            $start = request()->start_date;
            $end = request()->end_date;
            $this->purchases_returns->whereDate('transactions.transaction_date', '>=', $start)
                ->whereDate('transactions.transaction_date', '<=', $end);
        }

        $this->purchases_returns->groupBy(
            'transactions.id',
            // -----------muhib add this------------------------------
            'transactions.transaction_date',
            'transactions.ref_no',
            'contacts.name',
            'transactions.status',
            'transactions.payment_status',
            'transactions.final_total',
            'transactions.return_parent_id',
            'BS.name',
            'stores.name',
            'T.ref_no',
            // -----------------------------------------

        );
    }

    /**
     * Show the form for purchase return.
     *
     * @return \Illuminate\Http\Response
     */
    public function add(Request $request, $id)
    {
        if (! auth()->user()->can('purchase.update')) {
            abort(403, 'Unauthorized action.');
        }
        $business_id = request()->session()->get('user.business_id');

        $purchase = Transaction::where('business_id', $business_id)
            ->where('type', 'purchase')
            ->with([
                'purchase_lines',
                'contact',
                'tax',
                'return_parent',
                'purchase_lines.sub_unit',
                'purchase_lines.product',
                'purchase_lines.related_variations',
                'purchase_lines.related_variations.unit',
                'purchase_lines.product.unit'
            ])
            ->find($id);


        foreach ($purchase->purchase_lines as $key => $value) {
            if (! empty($value->sub_unit_id)) {
                $formated_purchase_line = $this->productUtil->changePurchaseLineUnit($value, $business_id);
                $purchase->purchase_lines[$key] = $formated_purchase_line;
            }
        }
        //dd( $purchase->purchase_lines);
        foreach ($purchase->purchase_lines as $key => $value) {
            $qty_available = $value->main_qty - $value->quantity_sold - $value->quantity_adjusted;

            $purchase->purchase_lines[$key]->formatted_qty_available = $this->transactionUtil->num_f($qty_available);
            if ($value->related_variations) {
                $purchase->purchase_lines[$key]->unit_variation_name = $value->related_variations->unit->short_name;
            }
        }
        $menuItems = $request->menuItems;

        return view('purchase_return.add')
            ->with(compact('purchase', 'menuItems'));
    }

    /**
     * Saves Purchase returns in the database.
     */
    public function store(Request $request)
    {
        if (! auth()->user()->can('purchase.update')) {
            abort(403, 'Unauthorized action.');
        }

        // dd($request->all());

        try {
            $business_id = request()->session()->get('user.business_id');

            $purchase = Transaction::where('business_id', $business_id)
                ->where('type', 'purchase')
                ->with(['purchase_lines', 'purchase_lines.sub_unit'])
                ->findOrFail($request->input('transaction_id'));

            $return_quantities = $request->input('returns');
            $return_total = 0;

            DB::beginTransaction();
            $productTaxesAmount = [];
            $productPurchasPrices = [];
            foreach ($purchase->purchase_lines as $purchase_line) {

                $old_return_qty = $purchase_line->quantity_returned;

                $return_quantity = ! empty($return_quantities[$purchase_line->id]) ? $this->productUtil->num_uf($return_quantities[$purchase_line->id]) : 0;

                $multiplier = 1;
                if (! empty($purchase_line->sub_unit->base_unit_multiplier)) {
                    $multiplier = $purchase_line->sub_unit->base_unit_multiplier;
                    $return_quantity = $return_quantity * $multiplier;
                }

                $purchase_line->quantity_returned = $return_quantity;
                $purchase_line->save();
                $return_total += $purchase_line->purchase_price_inc_tax * $purchase_line->quantity_returned;

                //Decrease quantity in variation location details
                if ($old_return_qty != $purchase_line->quantity_returned) {
                    $this->productUtil->decreaseProductQuantity(
                        $purchase_line->product_id,
                        $purchase_line->variation_id,
                        $purchase->location_id,
                        $purchase_line->quantity_returned,
                        $old_return_qty
                    );
                }
                $productPurchasPrices[$purchase_line->id] = $purchase_line->purchase_price * $purchase_line->quantity_returned;
                $productTaxesAmount[$purchase_line->id] = $purchase_line->item_tax * $purchase_line->quantity_returned;
            }
            $return_total_inc_tax = $return_total + $request->input('tax_amount');

            $return_transaction_data = [
                'total_before_tax' => $return_total,
                'final_total' => $return_total_inc_tax,
                'tax_amount' => $request->input('tax_amount'),
                'tax_id' => $purchase->tax_id
            ];

            if (empty($request->input('ref_no'))) {
                //Update reference count
                $ref_count = $this->transactionUtil->setAndGetReferenceCount('purchase_return');
                $return_transaction_data['ref_no'] = $this->transactionUtil->generateReferenceNumber('purchase_return', $ref_count);
            }

            $return_transaction = Transaction::where('business_id', $business_id)
                ->where('type', 'purchase_return')
                ->where('return_parent_id', $purchase->id)
                ->first();

            if (! empty($return_transaction)) {
                $return_transaction_before = $return_transaction->replicate();

                $return_transaction->update($return_transaction_data);

                $this->transactionUtil->activityLog($return_transaction, 'edited', $return_transaction_before);
            } else {
                $return_transaction_data['business_id'] = $business_id;
                $return_transaction_data['location_id'] = $purchase->location_id;
                // -------muhib add this----
                $return_transaction_data['store_id'] = $purchase->store_id;
                // ---------

                $return_transaction_data['type'] = 'purchase_return';
                $return_transaction_data['status'] = 'final';
                $return_transaction_data['contact_id'] = $purchase->contact_id;
                $return_transaction_data['transaction_date'] = now();
                $return_transaction_data['created_by'] = request()->session()->get('user.id');
                $return_transaction_data['return_parent_id'] = $purchase->id;
                $return_transaction_data['branch_cost_center_id'] = $purchase->branch_cost_center_id;
                $return_transaction_data['extra_cost_center_id'] = $purchase->extra_cost_center_id;


                // ------add this muhib---
                $return_transaction_data['essentials_duration'] = 0;
                // ----------
                $return_transaction = Transaction::create($return_transaction_data);

                $this->transactionUtil->activityLog($return_transaction, 'added');
            }
            // ----------------------------------------------Add JournalEntry-------------- \\
            $this->purchaseAccountingService->storeReturnPurchase(
                $purchase,
                $return_transaction,
                $productPurchasPrices,
                $productTaxesAmount
            );
            // ------------------------------------------------------------ \\
            //update payment status
            $this->transactionUtil->updatePaymentStatus($return_transaction->id, $return_transaction->final_total);

            $output = [
                'success' => 1,
                'msg' => __('lang_v1.purchase_return_added_success')
            ];


            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            dd($e->getMessage());
            logger()->emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

            $output = [
                'success' => 0,
                'msg' => __('messages.something_went_wrong')
            ];
        }

        return redirect('purchase-return')->with('status', $output);
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

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        if (! auth()->user()->can('purchase.view')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');

        $purchase = Transaction::where('business_id', $business_id)
            ->with([
                'return_parent',
                'return_parent.tax',
                'purchase_lines',
                'contact',
                'tax',
                'purchase_lines.sub_unit',
                'purchase_lines.product',
                'purchase_lines.product.unit'
            ])
            ->find($id);

        foreach ($purchase->purchase_lines as $key => $value) {
            if (! empty($value->sub_unit_id)) {
                $formated_purchase_line = $this->productUtil->changePurchaseLineUnit($value, $business_id);
                $purchase->purchase_lines[$key] = $formated_purchase_line;
            }
        }

        $purchase_taxes = [];
        if (! empty($purchase->return_parent->tax)) {
            if ($purchase->return_parent->tax->is_tax_group) {
                $purchase_taxes = $this->transactionUtil->sumGroupTaxDetails($this->transactionUtil->groupTaxDetails($purchase->return_parent->tax, $purchase->return_parent->tax_amount));
            } else {
                $purchase_taxes[$purchase->return_parent->tax->name] = $purchase->return_parent->tax_amount;
            }
        }

        //For combined purchase return return_parent is empty
        if (empty($purchase->return_parent) && ! empty($purchase->tax)) {
            if ($purchase->tax->is_tax_group) {
                $purchase_taxes = $this->transactionUtil->sumGroupTaxDetails($this->transactionUtil->groupTaxDetails($purchase->tax, $purchase->tax_amount));
            } else {
                $purchase_taxes[$purchase->tax->name] = $purchase->tax_amount;
            }
        }

        $activities = Activity::forSubject($purchase->return_parent)
            ->with(['causer', 'subject'])
            ->latest()
            ->get();
        $menuItems = $request->menuItems;

        return view('purchase_return.show')
            ->with(compact('purchase', 'purchase_taxes', 'activities', 'menuItems'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (! auth()->user()->can('purchase.delete')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            if (request()->ajax()) {
                $business_id = request()->session()->get('user.business_id');


                $purchase_return = Transaction::where('id', $id)
                    ->where('business_id', $business_id)
                    ->where('type', 'purchase_return')
                    ->with(['purchase_lines'])
                    ->first();

                DB::beginTransaction();

                if (empty($purchase_return->return_parent_id)) {
                    $delete_purchase_lines = $purchase_return->purchase_lines;
                    $delete_purchase_line_ids = [];
                    foreach ($delete_purchase_lines as $purchase_line) {
                        $delete_purchase_line_ids[] = $purchase_line->id;
                        $this->productUtil->updateProductQuantity($purchase_return->location_id, $purchase_line->product_id, $purchase_line->variation_id, $purchase_line->quantity_returned, 0, null, false);
                    }
                    PurchaseLine::where('transaction_id', $purchase_return->id)
                        ->whereIn('id', $delete_purchase_line_ids)
                        ->delete();
                } else {
                    $parent_purchase = Transaction::where('id', $purchase_return->return_parent_id)
                        ->where('business_id', $business_id)
                        ->where('type', 'purchase')
                        ->with(['purchase_lines'])
                        ->first();

                    $updated_purchase_lines = $parent_purchase->purchase_lines;
                    foreach ($updated_purchase_lines as $purchase_line) {
                        $this->productUtil->updateProductQuantity($parent_purchase->location_id, $purchase_line->product_id, $purchase_line->variation_id, $purchase_line->quantity_returned, 0, null, false);
                        $purchase_line->quantity_returned = 0;
                        $purchase_line->save();
                    }
                }

                //Delete Transaction
                $purchase_return->delete();

                //Delete account transactions
                AccountTransaction::where('transaction_id', $id)->delete();

                DB::commit();

                $output = [
                    'success' => true,
                    'msg' => __('lang_v1.deleted_success')
                ];
            }
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

            $output = [
                'success' => false,
                'msg' => __('messages.something_went_wrong')
            ];
        }

        return $output;
    }
}
