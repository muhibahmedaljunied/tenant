<?php

namespace App\Http\Controllers;

use Exception;
use App\Product;
use App\Business;
use App\Utils\Util;
use App\Models\AcMaster;

use App\BusinessLocation;
use App\Models\AcSetting;
use Illuminate\Http\Request;
use App\Models\AcJournalEntry;
use App\Models\AcCostCenBranche;
use App\Models\AcCostCenFieldAdd;
use Illuminate\Support\Facades\DB;

use App\Models\AcJournalEntryDetail;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\JournalEntriesImport;
use Yajra\DataTables\Facades\DataTables;
use Modules\ChartOfAccounts\Factories\OpeningBalanceFactory;

class AcJournalEntryController extends Controller
{
    protected $commonUtil;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(Util $commonUtil)
    {
        $this->commonUtil = $commonUtil;
    }

    public function index(Request $request)
    {
        $business_id = request()->session()->get('user.business_id');
        $business_locations = BusinessLocation::forDropdown($business_id, false);

        $entryType = "journal";

        if (request()->ajax()) {
            // $business_id = request()->session()->get('user.business_id');
            $business = Business::whereId($business_id)->value('owner_id');

            $isOwner = ($business == auth()->user()->id);

            // $ac_journal_entries = AcJournalEntry::withCount('ac_journal_entries')->withSum('ac_journal_entries', 'amount')->orderBy('id', 'desc')->get();
            $ac_journal_entries = AcJournalEntry::withCount('ac_journal_entries')
                ->withSum([
                    'ac_journal_entries' => fn($query) =>
                    $query->where(DB::raw('TRY_CAST(amount AS float)'), '>=', 0)
                        ->select(DB::raw('COALESCE(SUM(TRY_CAST(amount AS float)), 0)')),
                ], 'amount')
                ->withSum([
                    'ac_journal_entries' => fn($query) =>
                    $query->where(DB::raw('TRY_CAST(amount AS float)'), '<', 0)
                        ->select(DB::raw('COALESCE(SUM(TRY_CAST(amount AS float)), 0)')),
                ], 'amount_n')
                ->where('entry_type', '!=', 'opening')
                ->orderByDesc('id')
                ->get();


            $datatable = DataTables::of($ac_journal_entries)
                ->addColumn(
                    'action',
                    function ($dt) use ($isOwner) {
                        return view('ac_journal_entry.datatable.actions', compact('dt', 'isOwner'));
                    }
                )
                ->editColumn('entry_desc', ' {{ $entry_desc }} ')
                ->editColumn('entry_date', '@if($entry_date=="percentage") {{$entry_date}} @else {{$entry_date}} @endif ')
                ->editColumn('entry_type', '@if($entry_type=="daily") @lang("chart_of_accounts.entry_type_daily") @else @lang("chart_of_accounts.entry_type_opening") @endif ')
                ->editColumn('entry_no', '{{$sequence}} ')
                ->editColumn('entry_lines_no', ' {{ $ac_journal_entries_count  }} ')
                // ->editColumn('entry_total_term', '@if($ac_journal_entries_sum_amount >= 0) {{$ac_journal_entries_sum_amount/2}} @else {{($ac_journal_entries_sum_amount*-1)/2}} @endif ');
                ->editColumn('entry_total_term', '@if(isset($ac_journal_entries_sum_amount_n)) {{DisplayDouble(($ac_journal_entries_sum_amount + $ac_journal_entries_sum_amount_n*-1)/2)}} @else {{DisplayDouble($ac_journal_entries_sum_amount/2)}} @endif ');

            return $datatable->rawColumns(
                [
                    'action',
                    'entry_desc',
                    'entry_no',
                    'entry_date',
                    'entry_type',
                ]
            )
                ->make(true);
        }


        $menuItems = $request->menuItems;

        return view('ac_journal_entry.index', compact('entryType', 'menuItems'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $business_id = request()->session()->get('user.business_id');
        // $lastChildrenBranch = AcMaster::whereDoesntHave('parents')->pluck('account_name_ar', 'account_number');
        $lastChildrenBranch = AcMaster::whereDoesntHave('parents')->selectRaw("account_number, concat(account_name_ar, ' (', account_number , ')') as account_name_number")->pluck('account_name_number', 'account_number')->toArray();
        $ac_cost_cen_branches = AcCostCenBranche::where('business_id', $business_id)->orderBy('id', 'desc')->pluck('cost_description', 'id')->toArray();
        $ac_cost_cen_field_adds = AcCostCenFieldAdd::where('business_id', $business_id)->orderBy('id', 'desc')->pluck('cost_description', 'id')->toArray();

        $menuItems = $request->menuItems;

        return view('ac_journal_entry.create')
            ->with(compact('lastChildrenBranch', 'ac_cost_cen_branches', 'ac_cost_cen_field_adds', 'menuItems'));
    }

    public function journal_entry_row()
    {
        $output = [];

        if (request()->ajax()) {
            $business_id = request()->session()->get('user.business_id');
            $row_count = request()->get('journal_entry_row');
            $row_count = $row_count + 1;
            // $lastChildrenBranch = AcMaster::whereDoesntHave('parents')->pluck('account_name_ar', 'account_number');
            $lastChildrenBranch = AcMaster::whereDoesntHave('parents')->selectRaw("account_number, concat(account_name_ar, ' (', account_number , ')') as account_name_number")->pluck('account_name_number', 'account_number')->toArray();
            $ac_cost_cen_field_adds = AcCostCenFieldAdd::where('business_id', $business_id)->orderBy('id', 'desc')->pluck('cost_description', 'id')->toArray();
            $output['success'] = true;
            $output['msg'] = __('lang_v1.done');
            $output['html_content'] = view('ac_journal_entry.partials.journal_entry_row')
                ->with(compact('lastChildrenBranch', 'row_count', 'ac_cost_cen_field_adds'))->render();
        } else {
            $output['success'] = false;
            $output['msg'] = __('lang_v1.xxx');
        }


        return $output;
    }


    public function store(Request $request)
    {
        $input = $request->except('_token', 'submit_type');
        $request->validate([
            'entry_date' => 'required',
            'entry_desc' => 'nullable|string|min:3|max:500',
            'cost_cen_branche_id' => 'nullable|numeric|exists:ac_cost_cen_branches,id',
            'journal_entries' => 'required|array',
        ]);
        $journalEntries = $request->collect('journal_entries');
        $totalDebtor = $journalEntries->sum('debtor');
        $totalCreditor = $journalEntries->sum('creditor');

        if ($totalDebtor != $totalCreditor) {
            return back()->with('error', __('lang_v1.unbalanced_entry'))->withInput();
        }

        try {
            DB::beginTransaction();

            $ac_journal_entry_record = AcJournalEntry::create([
                'entry_no' => 1,
                'entry_date' => $this->commonUtil->uf_date($request->input('entry_date'), true),
                // ------------------------------------------ \\
                'entry_desc' => $input['entry_desc'],
                'cost_cen_branche_id' => $input['cost_cen_branche_id'],
                'entry_type' => "daily",
            ]);

            foreach ($input['journal_entries'] as $journal_entry) {

                $account_type = AcMaster::where('account_number', $journal_entry['account_number'])->value('account_type');
                $details['account_number'] = $journal_entry['account_number'];
                // if account_type is debtor
                if ($account_type == 'debtor') {
                    if ($journal_entry['debtor'] > 0) {
                        //+
                        $details['amount'] = $journal_entry['debtor'];
                    } else {
                        //-
                        $details['amount'] = $journal_entry['creditor'] * (-1);
                    }
                    // if account_type is creditor
                } else {
                    if ($journal_entry['creditor'] > 0) {
                        //+
                        $details['amount'] = $journal_entry['creditor'];
                    } else {
                        //-
                        $details['amount'] = $journal_entry['debtor'] * (-1);
                    }
                }
                $details['entry_desc'] = $journal_entry['entry_desc'];
                $details['cost_cen_field_id'] = $journal_entry['cost_cen_field_id'];
                $details['ac_journal_entries_id'] = $ac_journal_entry_record['id'];
                AcJournalEntryDetail::create($details);
            }
            DB::commit();
            $output = [
                'success' => true,
                'data' => $input,
                'msg' => __("lang_v1.success")
            ];
        } catch (Exception $e) {
            DB::rollback();
            logger()->emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage() . $e->getTraceAsString());

            $output = [
                'success' => false,
                'msg' => __("messages.something_went_wrong")
            ];
        }
        return redirect()
            ->action('AcJournalEntryController@index')
            ->with('status', $output);
    }

    public function createOpenAccountEntry()
    {
        $business_id = request()->session()->get('user.business_id');
        // $lastChildrenBranch = AcMaster::whereDoesntHave('parents')->pluck('account_name_ar', 'account_number');
        $lastChildrenBranch = AcMaster::whereDoesntHave('parents')->selectRaw("account_number, concat(account_name_ar, ' (', account_number , ')') as account_name_number")->pluck('account_name_number', 'account_number')->toArray();
        $ac_cost_cen_branches = AcCostCenBranche::userHaveAccess()->orderByDesc('id')->pluck('cost_description', 'id')->toArray();
        $ac_cost_cen_field_adds = AcCostCenFieldAdd::where('business_id', $business_id)->orderByDesc('id')->pluck('cost_description', 'id')->toArray();
        return view('ac_journal_entry.create_open_account_entry')
            ->with(compact('lastChildrenBranch', 'ac_cost_cen_branches', 'ac_cost_cen_field_adds'));
    }


    public function journal_entry_row_open_account()
    {
        $output = [];

        if (request()->ajax()) {
            $business_id = request()->session()->get('user.business_id');
            $row_count = request()->get('journal_entry_row');
            $row_count = $row_count + 1;
            // $lastChildrenBranch = AcMaster::whereDoesntHave('parents')->pluck('account_name_ar', 'account_number');
            $lastChildrenBranch = AcMaster::whereDoesntHave('parents')->selectRaw("account_number, concat(account_name_ar, ' (', account_number , ')') as account_name_number")->pluck('account_name_number', 'account_number')->toArray();
            $ac_cost_cen_field_adds = AcCostCenFieldAdd::where('business_id', $business_id)->orderBy('id', 'desc')->pluck('cost_description', 'id')->toArray();
            $output['success'] = true;
            $output['msg'] = __('lang_v1.done');
            $output['html_content'] = view('ac_journal_entry.partials.journal_entry_row_open_account')
                ->with(compact('lastChildrenBranch', 'row_count', 'ac_cost_cen_field_adds'))->render();
        } else {
            $output['success'] = false;
            $output['msg'] = __('lang_v1.xxx');
        }


        return $output;
    }

    public function storeOpenAccountEntry(Request $request)
    {
        $input = $request->except('_token', 'submit_type');
        // dd($request->all());
        try {

            $ac_journal_entry['entry_no'] = 1;
            $ac_journal_entry['entry_date'] = $this->commonUtil->uf_date($request->input('entry_date'), true);
            // 'operation_date' => $this->commonUtil->uf_date($request->input('entry_date'), true),
            $ac_journal_entry['entry_desc'] = $input['entry_desc'];
            $ac_journal_entry['entry_type'] = "opening";
            $ac_journal_entry['cost_cen_branche_id'] = $input['cost_cen_branche_id'];
            $ac_journal_entry['opening_account'] = $input['opening_balance_calculation'];
            $ac_journal_entry_record = AcJournalEntry::create($ac_journal_entry);

            // dd($ac_journal_entry_record['id']);
            $total_debtor = $input['total_debtor'];
            $total_creditor = $input['total_creditor'];

            foreach ($input['journal_entries'] as $journal_entry) {

                $account_type = AcMaster::where('account_number', $journal_entry['account_number'])->value('account_type');
                $details['account_number'] = $journal_entry['account_number'];
                // if account_type is debtor
                if ($account_type == 'debtor') {
                    if ($journal_entry['debtor'] > 0) {
                        //+
                        $details['amount'] = $journal_entry['debtor'];
                    } else {
                        //-
                        $details['amount'] = $journal_entry['creditor'] * (-1);
                    }
                    // if account_type is creditor
                } else {
                    if ($journal_entry['creditor'] > 0) {
                        //+
                        $details['amount'] = $journal_entry['creditor'];
                    } else {
                        //-
                        $details['amount'] = $journal_entry['debtor'] * (-1);
                    }
                }
                $details['entry_desc'] = $journal_entry['entry_desc'];
                $details['cost_cen_field_id'] = $journal_entry['cost_cen_field_id'];
                $details['ac_journal_entries_id'] = $ac_journal_entry_record['id'];
                // print_r($details);
                $ac_journal_entry_detail = AcJournalEntryDetail::create($details);
            }

            //for opening_balance_calculation
            $net = 0;
            $type = '';
            if ($total_debtor >= $total_creditor) {
                $net = $total_debtor - $total_creditor;
                $type = 'debtor';
            } else {
                $net = $total_creditor - $total_debtor;
                $type = 'creditor';
            }
            $account_number_entry_open = $input['opening_balance_calculation'];
            $opening_details['account_number'] = $account_number_entry_open;
            $account_type = AcMaster::where('account_number', $input['opening_balance_calculation'])->value('account_type');
            if ($account_type != $type) {
                $opening_details['amount'] = $net;
            } else {
                $opening_details['amount'] = $net * (-1);
            }
            $opening_details['entry_desc'] = $input['entry_desc'];
            $opening_details['ac_journal_entries_id'] = $ac_journal_entry_record['id'];

            $opening_deleted_acount = AcJournalEntryDetail::onlyTrashed()->where([['account_number', $account_number_entry_open], ['ac_journal_entries_id', $id]])->first();
            if ($opening_deleted_acount) {
                $opening_details['deleted_at'] = NULL;
                $opening_deleted_acount->update($opening_details);
            } else {
                $ac_journal_entry_opening_details = AcJournalEntryDetail::create($opening_details);
            }


            $output = [
                'success' => true,
                'data' => $input,
                'msg' => __("lang_v1.success")
            ];
        } catch (Exception $e) {
            logger()->emergency("File: {$e->getFile()} Line: {$e->getLine()} Message: {$e->getMessage()} | Trace : {$e->getTraceAsString()}");

            $output = [
                'success' => false,
                'msg' => __("messages.something_went_wrong")
            ];
        }
        return redirect()
            ->action('AcJournalEntryController@index')
            ->with('status', $output);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $ac_journal = AcJournalEntry::with('ac_journal_entries')->find($id);

        $lastChildrenBranch = AcMaster::whereDoesntHave('parents')->selectRaw("account_number, concat(account_name_ar, ' (', account_number , ')') as account_name_number")->pluck('account_name_number', 'account_number')->toArray();
        $menuItems = $request->menuItems;
        return view('ac_journal_entry.show_entry')
            ->with(compact('lastChildrenBranch', 'ac_journal', 'menuItems'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (request()->ajax()) {
            try {
                // $business_id = request()->user()->business_id;

                $ac_journal_entry_record = AcJournalEntry::findOrFail($id);
                $ac_journal_entry_record->delete();
                AcJournalEntryDetail::where('ac_journal_entries_id', $id)
                    ->delete();
                $output = [
                    'success' => true,
                    'msg' => __("lang_v1.success")
                ];
            } catch (Exception $e) {
                logger()->emergency("File: {$e->getFile()} Line: {$e->getLine()} Message: {$e->getMessage()} | Trace : {$e->getTraceAsString()}");

                $output = [
                    'success' => false,
                    'msg' => __("messages.something_went_wrong")
                ];
            }

            return $output;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $business_id = request()->session()->get('user.business_id');
        $ac_journal_entry_record = AcJournalEntry::findOrFail($id);
        $lastChildrenBranch = AcMaster::whereDoesntHave('parents')->selectRaw("account_number, concat(account_name_ar, ' (', account_number , ')') as account_name_number")->pluck('account_name_number', 'account_number')->toArray();
        $ac_cost_cen_branches = AcCostCenBranche::userHaveAccess()->orderBy('id', 'desc')->pluck('cost_description', 'id')->toArray();
        $ac_cost_cen_field_adds = AcCostCenFieldAdd::where('business_id', $business_id)->orderBy('id', 'desc')->pluck('cost_description', 'id')->toArray();

        return view('ac_journal_entry.edite')
            ->with(compact('lastChildrenBranch', 'ac_journal_entry_record', 'ac_cost_cen_branches', 'ac_cost_cen_field_adds'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input = $request->except('_token', 'submit_type');
        try {
            $ac_journal_entry = AcJournalEntry::findOrFail($id);
            $ac_journal_entry->update([
                'entry_date' => $this->commonUtil->uf_date($request->input('entry_date'), true),
                'entry_desc' => $input['entry_desc'],
                'cost_cen_branche_id' => $input['cost_cen_branche_id'],
                'entry_type' => "daily",
            ]);

            $journal_entry_detail_delete = AcJournalEntryDetail::where('ac_journal_entries_id', $id)->delete();

            if ($journal_entry_detail_delete) {
                foreach ($input['journal_entries'] as $journal_entry) {
                    $account_number_entry = $journal_entry['account_number'];
                    $deleted_acount = AcJournalEntryDetail::onlyTrashed()->where([['account_number', $account_number_entry], ['ac_journal_entries_id', $id]])->first();
                    // dd($deleted_acount);
                    $account_type = AcMaster::where('account_number', $journal_entry['account_number'])->value('account_type');
                    $details['account_number'] = $journal_entry['account_number'];
                    // if account_type is debtor
                    if ($account_type == 'debtor') {
                        if ($journal_entry['debtor'] > 0) {
                            //+
                            $details['amount'] = $journal_entry['debtor'];
                        } else {
                            //-
                            $details['amount'] = $journal_entry['creditor'] * (-1);
                        }
                        // if account_type is creditor
                    } else {
                        if ($journal_entry['creditor'] > 0) {
                            //+
                            $details['amount'] = $journal_entry['creditor'];
                        } else {
                            //-
                            $details['amount'] = $journal_entry['debtor'] * (-1);
                        }
                    }
                    $details['entry_desc'] = $journal_entry['entry_desc'];
                    $details['cost_cen_field_id'] = $journal_entry['cost_cen_field_id'];
                    $details['ac_journal_entries_id'] = $id;

                    if ($deleted_acount) {
                        $details['deleted_at'] = NULL;
                        // $update_delete_ac_journal_entry = AcJournalEntryDetail::findOrFail($deleted_acount->id);
                        // dd($update_delete_ac_journal_entry);
                        $deleted_acount->update($details);
                    } else {
                        $ac_journal_entry_detail = AcJournalEntryDetail::create($details);
                    }
                }
            }
            $output = [
                'success' => true,
                'data' => $input,
                'msg' => __("lang_v1.success")
            ];
        } catch (Exception $e) {
            logger()->emergency("File: {$e->getFile()} Line: {$e->getLine()} Message: {$e->getMessage()} | Trace : {$e->getTraceAsString()}");

            $output = [
                'success' => false,
                'msg' => __("messages.something_went_wrong")
            ];
        }
        return redirect()
            ->action('AcJournalEntryController@index')
            ->with('status', $output);
    }

    public function editOpenAccountEntry(Request $request, $id)
    {
        $business_id = request()->session()->get('user.business_id');
        $ac_journal_entry_record = AcJournalEntry::findOrFail($id);
        $lastChildrenBranch = AcMaster::whereDoesntHave('parents')->selectRaw("account_number, concat(account_name_ar, ' (', account_number , ')') as account_name_number")->pluck('account_name_number', 'account_number')->toArray();
        $ac_cost_cen_branches = AcCostCenBranche::userHaveAccess()->where('business_id', $business_id)->orderBy('id', 'desc')->pluck('cost_description', 'id')->toArray();
        $ac_cost_cen_field_adds = AcCostCenFieldAdd::where('business_id', $business_id)->orderBy('id', 'desc')->pluck('cost_description', 'id')->toArray();
        $menuItems = $request->menuItems;
        return view('ac_journal_entry.edite_open_account_entry')
            ->with(compact('lastChildrenBranch', 'ac_journal_entry_record', 'ac_cost_cen_branches', 'ac_cost_cen_field_adds', 'menuItems'));
    }

    public function updateOpenAccountEntry(Request $request, $id)
    {
        $input = $request->except('_token', 'submit_type');
        // dd($request->all());
        try {
            $ac_journal_entry = AcJournalEntry::findOrFail($id);
            $input_update['entry_date'] = $this->commonUtil->uf_date($request->input('entry_date'), true);
            $input_update['entry_desc'] = $input['entry_desc'];
            $input_update['entry_type'] = "opening";
            $input_update['cost_cen_branche_id'] = $input['cost_cen_branche_id'];
            $input_update['opening_account'] = $input['opening_balance_calculation'];
            $ac_journal_entry->update($input_update);
            $journal_entry_detail_delete = AcJournalEntryDetail::where('ac_journal_entries_id', $id)->delete();

            $total_debtor = $input['total_debtor'];
            $total_creditor = $input['total_creditor'];
            if ($journal_entry_detail_delete) {
                foreach ($input['journal_entries'] as $journal_entry) {
                    $account_number_entry = $journal_entry['account_number'];
                    $deleted_acount = AcJournalEntryDetail::onlyTrashed()->where([['account_number', $account_number_entry], ['ac_journal_entries_id', $id]])->first();
                    $account_type = AcMaster::where('account_number', $journal_entry['account_number'])->value('account_type');
                    $details['account_number'] = $journal_entry['account_number'];
                    // if account_type is debtor
                    if ($account_type == 'debtor') {
                        if ($journal_entry['debtor'] > 0) {
                            //+
                            $details['amount'] = $journal_entry['debtor'];
                        } else {
                            //-
                            $details['amount'] = $journal_entry['creditor'] * (-1);
                        }
                        // if account_type is creditor
                    } else {
                        if ($journal_entry['creditor'] > 0) {
                            //+
                            $details['amount'] = $journal_entry['creditor'];
                        } else {
                            //-
                            $details['amount'] = $journal_entry['debtor'] * (-1);
                        }
                    }
                    $details['entry_desc'] = $journal_entry['entry_desc'];
                    $details['cost_cen_field_id'] = $journal_entry['cost_cen_field_id'];
                    $details['ac_journal_entries_id'] = $id;
                    if ($deleted_acount) {
                        $details['deleted_at'] = NULL;
                        $deleted_acount->update($details);
                    } else {
                        $ac_journal_entry_detail = AcJournalEntryDetail::create($details);
                    }
                }
            }

            //for opening_balance_calculation
            $net = 0;
            $type = '';
            if ($total_debtor >= $total_creditor) {
                $net = $total_debtor - $total_creditor;
                $type = 'debtor';
            } else {
                $net = $total_creditor - $total_debtor;
                $type = 'creditor';
            }
            $opening_details['account_number'] = $input['opening_balance_calculation'];
            $account_type = AcMaster::where('account_number', $input['opening_balance_calculation'])->value('account_type');
            if ($account_type != $type) {
                $opening_details['amount'] = $net;
            } else {
                $opening_details['amount'] = $net * (-1);
            }
            $opening_details['entry_desc'] = $input['entry_desc'];
            $opening_details['ac_journal_entries_id'] = $id;
            $ac_journal_entry_opening_details = AcJournalEntryDetail::create($opening_details);


            $output = [
                'success' => true,
                'data' => $input,
                'msg' => __("lang_v1.success")
            ];
        } catch (Exception $e) {
            logger()->emergency("File: {$e->getFile()} Line: {$e->getLine()} Message: {$e->getMessage()} | Trace : {$e->getTraceAsString()}");

            $output = [
                'success' => false,
                'msg' => __("messages.something_went_wrong")
            ];
        }
        return redirect()
            ->action('AcJournalEntryController@index')
            ->with('status', $output);
    }

    public function openingBalance(Request $request)
    {
        $menuItems = $request->menuItems;
        return view('ac_journal_entry.opening_balance', compact('menuItems'));
    }

    public function open_period() {



    }


    public function close_period() {

        
    }

    public function createOpeningBalance($type)
    {
        $business_id = request()->session()->get('user.business_id');
        $setting = AcSetting::where('business_id', request()->session()->get('user.business_id'))->first();
        $data = [];
        if ($type === 'customers') {
            $data['customers'] = AcMaster::forDropdown($setting->customers);
        } else if ($type === 'vendors') {
            $data['vendors'] = AcMaster::forDropdown($setting->suppliers);
        } else if ($type === 'products') {
            $data['locations'] = BusinessLocation::forDropdown($business_id);
            $data['products'] = Product::where('business_id', $business_id)->pluck('name', 'id');
        }
        $data['accounts'] = AcMaster::whereDoesntHave('parents')->selectRaw("account_number, concat(account_name_ar, ' (', account_number , ')') as account_name_number")->pluck('account_name_number', 'account_number')->toArray();
        $data['type'] = $type;
        return view('ac_journal_entry.opening_balance.create', $data);
    }

    public function storeOpeningBalance(Request $request, $type)
    {
        $request->validate([
            'date' => 'required'
        ]);
        OpeningBalanceFactory::factory($type)->createMany($request->except('accounts'), $request->input('accounts'));
        return redirect()->action('OpeningEntryController@index');
    }
    public function importView(Request $request)
    {

        $menuItems = $request->menuItems;
        return view('ac_journal_entry.imports.index', compact('menuItems'));
    }
    public function import(Request $request)
    {
        try {
            // ---------------------------------------------------- \\
            //Set maximum php execution time
            ini_set('max_execution_time', 0);
            ini_set('memory_limit', -1);
            // ---------------------------------------------------- \\
            DB::beginTransaction();
            // ---------------------------------------------------- \\
            $journalImport = new JournalEntriesImport();
            Excel::import($journalImport, $request->file('import_file'));
            // ---------------------------------------------------- \\
            DB::commit();
            // ---------------------------------------------------- \\
            $output = [
                'success' => 1,
                'msg' => __('chart_of_accounts.file_imported_successfully'),
                'info' => $journalImport->getInfo()
            ];
        } catch (Exception $e) {
            // throw $e;
            DB::rollBack();
            logEmergency($e);
            $output = [
                'success' => 0,
                'msg' => __('chart_of_accounts.file_imported_failed')
            ];
        }
        return redirect(route('ac_journal.import.view'))->with('status', $output);
    }
}
