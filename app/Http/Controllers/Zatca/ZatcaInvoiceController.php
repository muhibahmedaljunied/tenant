<?php

namespace App\Http\Controllers\Zatca;

use Exception;
use App\Transaction;
use App\Models\Zatca;
use App\Utils\BusinessUtil;

use App\Models\ZatcaInvoice;
use Illuminate\Http\Response;
use App\Utils\TransactionUtil;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\ZatcaSettingRequest;
use Illuminate\Http\Request;

class ZatcaInvoiceController extends Controller
{
    protected $transactionUtil;
    protected $businessUtil;
    public function __construct(
        TransactionUtil $transactionUtil,
        BusinessUtil $businessUtil
    ) {
        $this->transactionUtil = $transactionUtil;
        $this->businessUtil = $businessUtil;
    }
    public function index(Request $request)
    {
        // Clear => B2B 
        // REPORT => B2C

        if (request()->ajax()) {
            $transactionsTypes = Transaction::transactionTypes();
            $zatcas = ZatcaInvoice::query()->with('zatca:id,egs_serial_number', 'transaction:id,type')->latest('id');
            return Datatables::of($zatcas)
                ->removeColumn('id')
                ->editColumn('action', fn($invoice) => view('zatca_invoices.datatable.action', compact('invoice')))
                ->editColumn('sent_to_zatca', function ($invoice) {
                    return view('zatca_invoices.datatable.sent_status', [
                        'sent_to_zatca' => $invoice->sent_to_zatca
                    ]);
                })
                ->addColumn('transaction_type', fn($invoice) => $transactionsTypes[$invoice->transaction->type])
                ->editColumn('sent_to_zatca_status', function ($invoice) {
                    return view('zatca_invoices.datatable.status', [
                        'status' => strtolower($invoice->sent_to_zatca_status)
                    ]);
                })
                ->editColumn('egs_serial_number', fn($invoice) => $invoice->zatca->egs_serial_number)
                ->rawColumns([
                    'invoiceNumber',
                    'issue_at',
                    'transaction_type',
                    'egs_serial_number',
                    'total',
                    'tax_total',
                    'total_discount',
                    'total_net',
                    'sent_to_zatca',
                    'sent_to_zatca_status',
                    'action'
                ])->make(true);
        }
                $menuItems = $request->menuItems;

        return view('zatca_invoices.index',compact('menuItems'));
    }
    public function create()
    {
    }

    public function send()
    {
    }


    public function edit($zatca)
    {
    }
    public function show(ZatcaInvoice $invoice)
    {
        $invoice->load('transaction:id,type', 'responses');
        switch ($invoice->transaction->type) {
            case 'sell':
                return redirect()->action('SellController@show', $invoice->transaction->id);
            case 'sell_return':
                return redirect()->action('SellReturnController@show', $invoice->transaction->id);
            default:
                return abort(Response::HTTP_NOT_FOUND);
        }

    }

    public function update(ZatcaSettingRequest $request, Zatca $zatca)
    {
    }

    public function destroy(ZatcaInvoice $invoice)
    {
        $this->authorize('chartofaccounts.delete');
        if (request()->ajax()) {
            try {

                $invoice->delete();

                $output = [
                    'success' => true,
                    'msg' => __("lang_v1.success")
                ];
            } catch (Exception $e) {
                logger()->emergency("File: {$e->getFile()} Line: {$e->getLine()} Message: {$e->getMessage()}");

                $output = [
                    'success' => false,
                    'msg' => __("messages.something_went_wrong")
                ];
            }

            return $output;
        }
    }
}
