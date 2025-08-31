<?php

namespace App\Http\Controllers;

use Exception;
use App\Contact;
use App\Business;
use Carbon\Carbon;
use App\Models\Payment;
use App\Models\AcMaster;
use App\Models\AcSetting;
use Illuminate\Http\Request;
use App\Models\AcJournalEntry;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\AcCostCenBranche;
use App\Models\AcCostCenFieldAdd;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\RedirectResponse;
use App\Services\Payment\PaymentService;

class PaymentController extends Controller
{
    protected $paymentService;
    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }
    public function index(Request $request, $type)
    {
        if (request()->ajax()) {
            $type = $type === 'sales' ? 'customer' : 'vendor';
            $roles = Payment::where('contact_type', $type)->select('contact_id', 'reference_number', 'id', 'type', 'description', 'date', 'amount');

            return \Yajra\DataTables\Facades\DataTables::of($roles)
                ->editColumn(
                    'amount',
                    '<div style="white-space: nowrap;">@format_currency($amount)</div>'
                )
                ->editColumn('date', function (Payment $payment) {
                    return $payment->date->format('d-m-Y');
                })
                ->editColumn('contact_id', function (Payment $payment) {
                    if ($payment->contact) {
                        return $payment->contact->name;
                    }
                    return "غير معروف";
                })
                ->addColumn('action', function ($row) {
                    $action = '<a href="' . action('PaymentController@show', [$row->id]) . '" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-eye-open"></i> ' . __("messages.view") . '</a>';
                    $action .= '<a href="' . action('PaymentController@print', [$row->id]) . '" target="_blank" class="btn btn-xs btn-info mr-8"><i class="glyphicon glyphicon-eye-open"></i> ' . __("messages.print") . '</a>';
                    $action .= '<a href="' . action('PaymentController@print', ['payment' => $row->id, 'ext' => '.pdf']) . '" target="_blank" class="btn btn-xs btn-info mr-8"><i class="glyphicon glyphicon-eye-open"></i> ' . __("messages.print") . '</a>';
                    $action .= '<button data-href="' . action('PaymentController@destroy', [$row->id]) . '" class="btn btn-xs btn-danger delete_role_button mr-8"><i class="glyphicon glyphicon-trash"></i> ' . __("messages.delete") . '</button>';
                    return $action;
                })
                ->removeColumn('id')
                ->rawColumns([5, 6])
                ->make(false);
        }
        $menuItems = $request->menuItems;
        return view('payments.index', compact('type', 'menuItems'));
    }


    public function show(Payment $payment)
    {
        return view('payments.show', compact('payment'));
    }

    public function create(Request $request,$type)
    {
        $business_id = request()->session()->get('user.business_id');
        $payment = Payment::max('reference_number');
        preg_match('/[0-9]+/', $payment, $matches);
        $reference_number = count($matches) ? (int)$matches[0] : 0;
        $ref_num = "PYT1";
        if ($payment) {
            $ref_num = "PYT" . ($reference_number + 1);
        }
        if ($type === 'sales') {
            $contacts = Contact::customersDropdown($business_id);
        } else {
            $contacts = Contact::suppliersDropdown($business_id);
        }
        $setting = AcSetting::where('business_id', $business_id)->first();
        $accounts = AcMaster::forDropdown($setting->cash_equivalents);
        $debtAges = AcJournalEntry::debtAges();

        $types = ['receive' => __('payment.receive'), 'send' => __('payment.send')];
        $branch_cost_centers = AcCostCenBranche::forDropdown($business_id);
        $extra_cost_centers  = AcCostCenFieldAdd::forDropdown($business_id);
           $menuItems = $request->menuItems;
        return view('payments.create', compact(
            'ref_num',
            'debtAges',
            'contacts',
            'accounts',
            'types',
            'type',
            'branch_cost_centers',
            'extra_cost_centers',
            'menuItems'
        ));
    }

    public function store(Request $request, $type): RedirectResponse
    {
        try {

            DB::beginTransaction();

            $date = Carbon::parse($request->input('date'));
            $description = $request->input('description');
            $amount  = (float)$request->input('amount');
            $contact = Contact::find($request->input('contact'));
            $account = AcMaster::find($request->input('account'));

            if ($contact->type === 'both') {
                $account_number = ($type === 'sales') ? $contact->account_number_customer : $contact->account_number_supplier;
                $contact_type = ($type === 'sales') ? 'customer' : 'vendor';
            } else {
                $account_number = ($contact->type === 'customer') ? $contact->account_number_customer : $contact->account_number_supplier;
                $contact_type = ($contact->type === 'customer') ? 'customer' : 'vendor';
            }

            $this->paymentService->createPayment($request, $date, $contact_type, $amount, $account);
            $ac_journal_entry_inv['entry_no'] = 1;
            $ac_journal_entry_inv['entry_date'] = $date;
            $description = "سند: {$description}";



            if ($request->has('debtAges')) {
                $deptRangesPay = $this->paymentService->debtAges($account_number, $amount, $request->input('type'), $account->account_type);
                $entry = $this->paymentService->entryDeptPay($ac_journal_entry_inv, $account, $type, $request, $description, $account_number, $contact_type, $amount, $date);
                foreach ($deptRangesPay as $date => $pay) {
                    $this->paymentService->createDeptPay($entry, $type, $pay, $contact_type, $request, $date);
                }
            } else {
                $this->paymentService->entryDeptPay($ac_journal_entry_inv, $account, $type, $request, $description, $account_number, $contact_type, $amount, null);
            }

            DB::commit();

            $output = [
                'success' => 1,
                'msg' => __('lang_v1.success')
            ];
            return redirect()->action('PaymentController@index', ['type' => $type])->with('status', $output);
        } catch (Exception $e) {

            DB::rollBack();
            logger()->emergency("File: {$e->getFile()} Line: {$e->getLine()} Message: {$e->getMessage()}");

            $output = [
                'success'   => 0,
                'msg'       => $e->getMessage()
            ];

            return redirect()->back()->with('status', $output);
        }
    }

    public function print(Payment $payment, $ext = "")
    {

        $business = Business::find(request()->session()->get('user.business_id'));
        $data = compact('payment', 'business');
        if ($ext === ".pdf") {
            //            $for_pdf = true;
            //            $data['for_pdf'] = true;
            //            $html = view('payments.print')
            //                ->with($data)->render();
            //            $mpdf = $this->getMpdf();
            //            $mpdf->WriteHTML($html);
            //            $mpdf->Output();
            $pdf = Pdf::loadView('payments.print', $data);
            return $pdf->download("Receipt-$payment->reference_number.pdf");
        }
        return view('payments.print', $data);
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return response()->json([
            'status' => true,
            'msg' => __("lang_v1.deleted_success")
        ]);
    }
}
