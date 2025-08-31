<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Utils\Util;
use App\Models\AcMaster;
use App\Models\AcSetting;
use Illuminate\Http\Request;
use App\Models\AcCostCenBranche;
use App\Models\AcCostCenFieldAdd;
use Illuminate\Support\Facades\DB;
use App\Models\AcJournalEntryDetail;
use App\Services\AccountStatment\AccountStatementService;
use stdClass;

class AcReportController extends Controller
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
        return $this->JournalLedgerReport($request);
    }

    public function JournalLedgerReport(Request $request)
    {
        $this->authorize('journal_ledger.access');
        // dd($request->all());
        $business_id = request()->session()->get('user.business_id');
        $first_day = Carbon::now()->startOfYear()->format('Y-m-d');
        $now_date = Carbon::now()->endOfYear()->format('Y-m-d');
        // $now_date = date('Y-m-d');
        $date_from = isset($request->ar_date_from) ? date('Y-m-d', strtotime($request->ar_date_from)) : $first_day;
        $date_to = isset($request->ar_date_to) ? date('Y-m-d', strtotime($request->ar_date_to)) : $now_date;
        $accountStatementService = new AccountStatementService();

        $branch_cost_center = $request->input('branch_cost_center');
        $field_cost_center = $request->input('field_cost_center');
        $branch_cost_centers = [];
        $field_cost_centers = [];
        if ($request->filled('branch_cost_center')) {
            $branch_cost_centers = $accountStatementService->getSelfCostCenterBranchParents($branch_cost_center);
        }
        if ($request->filled('field_cost_center')) {
            $field_cost_centers = $accountStatementService->getSelfCostCenterFieldsParents($field_cost_center);
        }
        // $positive_debtor = AcJournalEntryDetail::where('amount', '>', 0)
        //     ->forJournalEntry([$date_from, $date_to], $branch_cost_centers)
        //     ->forAccountType($field_cost_centers, 'debtor')
        //     ->sum('amount');

        $positive_debtor = AcJournalEntryDetail::select(DB::raw('SUM(TRY_CAST(amount AS float)) AS aggregate'))
            ->whereRaw('TRY_CAST(amount AS float) > 0')
            ->forJournalEntry([$date_from, $date_to], $branch_cost_centers)
            ->forAccountType($field_cost_centers, 'debtor')
            ->value('aggregate');



        $negative_debtor = AcJournalEntryDetail::where('amount', '<', 0);

        // debter sum

        $negative_debtor = AcJournalEntryDetail::select(DB::raw('SUM(TRY_CAST(amount AS float)) AS aggregate'))
            ->whereRaw('TRY_CAST(amount AS float) < 0')
            ->forJournalEntry([$date_from, $date_to], $branch_cost_centers)
            ->forAccountType($field_cost_centers, 'debtor')
            ->value('aggregate');


        // creditor sum
        // $positive_creditor = AcJournalEntryDetail::where('amount', '>', 0)
        //     ->forJournalEntry([$date_from, $date_to], $branch_cost_centers)
        //     ->forAccountType($field_cost_centers, 'creditor')
        //     ->sum('amount');

        $positive_creditor = AcJournalEntryDetail::select(DB::raw('SUM(TRY_CAST(amount AS float)) AS aggregate'))
            ->whereRaw('TRY_CAST(amount AS float) > 0')
            ->forJournalEntry([$date_from, $date_to], $branch_cost_centers)
            ->forAccountType($field_cost_centers, 'creditor')
            ->value('aggregate');



        $negative_creditor = AcJournalEntryDetail::select(DB::raw('SUM(TRY_CAST(amount AS float)) AS aggregate'))
            ->whereRaw('TRY_CAST(amount AS float) < 0')
            ->forJournalEntry([$date_from, $date_to], $branch_cost_centers)
            ->forAccountType($field_cost_centers, 'creditor')
            ->value('aggregate');


        $creditor = $positive_creditor + $negative_debtor * (-1);
        $debtor = $positive_debtor + $negative_creditor * (-1);

        $all_masters = $accountStatementService->getAccountMaster()->whereNull('parent_acct_no')->values();
        $ac_cost_cen_branches = AcCostCenBranche::userHaveAccess()->orderByDesc('id')->pluck('cost_description', 'id')->toArray();
        $ac_cost_cen_field_adds = AcCostCenFieldAdd::orderBy('id')->pluck('cost_description', 'id')->toArray();
        $menuItems = $request->menuItems;
        return view('ac_reports.index', compact(
            'all_masters',
            'accountStatementService',
            'ac_cost_cen_branches',
            'ac_cost_cen_field_adds',
            'creditor',
            'debtor',
            'date_from',
            'date_to',
            'field_cost_center',
            'branch_cost_center',
            'menuItems'
        ));
    }

    public function AccountStatementReport(Request $request)
    {
        $this->authorize('account_statement.access');
        $business_id = request()->session()->get('user.business_id');

        $first_day = now()->startOfYear()->format('Y-m-d');
        $now_date = now()->endOfYear()->format('Y-m-d');

        $date_from = isset($request->ar_date_from) ? date('Y-m-d', strtotime($request->ar_date_from)) : $first_day;
        $date_to = isset($request->ar_date_to) ? date('Y-m-d', strtotime($request->ar_date_to)) : $now_date;
        $accountStatementService = new AccountStatementService();

        $previous_date = Carbon::parse($date_from)->subDays(1)->format('Y-m-d');

        $filter_type = $request->input('filter_type');
        $cost_center = $request->input('cost_center');

        $journal_entries = null;
        $account_number = isset($request->account_number) ? $request->account_number : '';
        $int_sec_acc_number = isset($request->int_sec_acc_number) ? $request->int_sec_acc_number : '';
        $filter_from_acc = isset($request->filter_from_acc) ? $request->filter_from_acc : '';
        $filter_to_acc = isset($request->filter_to_acc) ? $request->filter_to_acc : '';
        $accounts_range = [];

        if ($filter_type == 'accounts_group') {
            if (isset($request->filter_from_acc) && isset($request->filter_to_acc)) {
                $account_number = '';
                $filter_from_acc_details = $accountStatementService->getAccountMaster()->where('account_number', $filter_from_acc)->first();
                $filter_to_acc_details = $accountStatementService->getAccountMaster()->where('account_number', $filter_to_acc)->first();
                // make array of parents for filter_from_acc
                $parents_of_from_acc = [];
                $parents_of_from_acc[] = $filter_from_acc_details->account_number;
                $parent_acct_from = $filter_from_acc_details->parent_acct_no;
                while ($parent_acct_from) {
                    $parents_of_from_acc[] = $parent_acct_from;
                    $accounts_detials = $accountStatementService->getAccountMaster()->where('account_number', $parent_acct_from)->first();
                    $parent_acct_from = $accounts_detials->parent_acct_no;
                }
                // make array of parents for filter_to_acc
                $parents_of_to_acc = [];
                $parents_of_to_acc[] = $filter_to_acc_details->account_number;
                $parent_acct_to = $filter_to_acc_details->parent_acct_no;
                while ($parent_acct_to) {
                    $parents_of_to_acc[] = $parent_acct_to;
                    $accounts_detials = $accountStatementService->getAccountMaster()->where('account_number', $parent_acct_to)->first();
                    $parent_acct_to = $accounts_detials->parent_acct_no;
                }
                // $lastChildrenBranchForOne = AcMaster::whereDoesntHave('parents')->where('parent_acct_no', $account_number)->get();
                $first_par_root_main = min($parents_of_from_acc);
                $accounts_range = $accountStatementService->searchInBranchWithRange($first_par_root_main, $filter_from_acc, $filter_to_acc);


                if ($filter_from_acc_details->parent_acct_no == $filter_to_acc_details->parent_acct_no) {
                    $journal_entries = AcJournalEntryDetail::whereBetween('account_number', [$filter_from_acc, $filter_to_acc])
                        ->whereHas('ac_journal_entry', function ($date) use ($date_from, $date_to) {
                            $date->whereBetween('entry_date', [$date_from, $date_to]);
                        })->get()
                        ->groupBy('account_number');
                } else {
                    $journal_entries = AcJournalEntryDetail::whereIn('account_number', $accounts_range)
                        ->whereHas('ac_journal_entry', function ($date) use ($date_from, $date_to) {
                            $date->whereBetween('entry_date', [$date_from, $date_to]);
                        })
                        ->get()
                        ->groupBy('account_number');
                }
            }
        }
        $filter_from_acc = '';
        $filter_to_acc = '';
        $branch_cost_centers = [];
        $field_cost_centers = [];
        $branch_cost_center = $request->input('branch_cost_center');
        $field_cost_center = $request->input('field_cost_center');
        if ($request->filled('account_number')) {
            $accounts_range[] = $request->account_number;
        }
        if ($request->filled('branch_cost_center')) {
            $branch_cost_centers = $accountStatementService->getSelfCostCenterBranchParents($branch_cost_center);
        }
        if ($request->filled('field_cost_center')) {

            $field_cost_centers = $accountStatementService->getSelfCostCenterFieldsParents($field_cost_center);
        }
        $journal_entries = AcJournalEntryDetail::whereIn('account_number', $accounts_range)
            ->forJournalEntry([$date_from, $date_to], $branch_cost_centers)
            ->forAccountType($field_cost_centers)

            ->get()
            ->groupBy('account_number');


        if ($journal_entries) {
            $journal_entries = $journal_entries->reverse();
        }

        $accountsMasters = $accountStatementService->formatParents();
        $lastChildrenBranch = $accountsMasters->filter(fn($item) => empty($item->subParents))->map(fn($item) => [
            'account_number' => $item->account_number,
            'account_name_number' => "{$item->account_name_ar} ({$item->account_number})"
        ])->pluck('account_name_number', 'account_number')->toArray();


        $ac_cost_cen_branches = $accountStatementService->getCostBranches()->sortByDesc('id')->pluck('cost_description', 'id')->toArray();
        $ac_cost_cen_field_adds = $accountStatementService->getCostCenFieldAdd()->sortByDesc('id')->pluck('cost_description', 'id')->toArray();


        $menuItems = $request->menuItems;
        return view(
            'ac_reports.account_statement',
            compact(
                'lastChildrenBranch',
                'accountStatementService',
                'date_from',
                'date_to',
                'accounts_range',
                'journal_entries',
                'account_number',
                'int_sec_acc_number',
                'previous_date',
                'ac_cost_cen_branches',
                'ac_cost_cen_field_adds',
                'filter_type',
                'branch_cost_center',
                'field_cost_center',
                'filter_from_acc',
                'filter_to_acc',
                'menuItems'
            )
        );
    }
    public function AccountStatementReportClient(Request $request)
    {
        $this->authorize('account_statement.access');
        return $this->accountStatementReportService($request, 'debtor', '110201', 'ac_reports.account_statement_client');
    }
    public function AccountStatementReportSupplier(Request $request)
    {
        $this->authorize('account_statement.access');
        return $this->accountStatementReportService($request, 'creditor', '210101', 'ac_reports.account_statement_supplier');
    }


    public function TrialBalanceReport(Request $request)
    {
        $this->authorize('trial_balance.access');
        $business_id = request()->session()->get('user.business_id');
        $accountStatementService = new AccountStatementService;

        $first_day = now()->startOfYear()->format('Y-m-d');
        $now_date = now()->endOfYear()->format('Y-m-d');

        $date_from = isset($request->ar_date_from) ? date('Y-m-d', strtotime($request->ar_date_from)) : $first_day;
        $date_to = isset($request->ar_date_to) ? date('Y-m-d', strtotime($request->ar_date_to)) : $now_date;

        $branch_cost_centers = [];
        $field_cost_centers = [];

        if ($request->filled('branch_cost_center')) {
            $branch_cost_centers = $accountStatementService->getSelfCostCenterBranchParents($request->input('branch_cost_center'));
        }

        if ($request->filled('field_cost_center')) {
            $field_cost_centers = $accountStatementService->getSelfCostCenterFieldsParents($request->input('field_cost_center'));
        }

        $previous_date = Carbon::parse($date_from)->subDays(1)->format('Y-m-d');

        // debter sum for in period search

        $debtJournalEntries = AcJournalEntryDetail::select('amount')
            ->forJournalEntry([$date_from, $date_to], $branch_cost_centers)
            ->forAccountType($field_cost_centers);

        $debtJournalEntries = collect(DB::select($debtJournalEntries->toSql(), $debtJournalEntries->getBindings()));
        $positive_debtor_root_main = $debtJournalEntries->where('amount', '>', 0)->sum(fn($item) => doubleval($item instanceof stdClass ? $item->amount : $item['amount']));

        $negative_debtor_root_main = $debtJournalEntries->where('amount', '<', 0)->sum(fn($item) => doubleval($item instanceof stdClass ? $item->amount : $item['amount']));

        // creditor sum for in period search
        $positive_creditor_root_main = $debtJournalEntries->where('amount', '>', 0)->sum(fn($item) => doubleval($item instanceof stdClass ? $item->amount : $item['amount']));

        $negative_creditor_root_main = $debtJournalEntries->where('amount', '<', 0)->sum(fn($item) => doubleval($item instanceof stdClass ? $item->amount : $item['amount']));

        $creditor_root_main = $positive_creditor_root_main + $negative_debtor_root_main * (-1);
        $debtor_root_main = $positive_debtor_root_main + $negative_creditor_root_main * (-1);

        // debter sum  before start period search
        $openedDebtorEntries = AcJournalEntryDetail::whereHas('ac_journal_entry', function ($date) use ($previous_date, $branch_cost_centers) {
            if (count($branch_cost_centers)) {
                $date->whereDate('entry_date', '<=', $previous_date)->whereIn('cost_cen_branche_id', $branch_cost_centers);
            } else {
                $date->whereDate('entry_date', '<=', $previous_date);
            }
        })
            ->forAccountType($field_cost_centers, ['debtor', 'creditor'])
            ->addSelect([
                'account_master_type' => AcMaster::whereColumn('account_number', 'ac_masters.account_number')->select('account_type')->limit(1)
            ])
            ->select('amount');
        $openedDebtorEntries = collect(DB::select($openedDebtorEntries->toSql(), $openedDebtorEntries->getBindings()));
        $positive_debtor_root_open = $openedDebtorEntries->where('account_master_type', 'debtor')->where('amount', '>', 0)->sum(fn($item) => doubleval($item instanceof stdClass ? $item->amount : $item['amount']));

        $negative_debtor_root_open = $openedDebtorEntries->where('account_master_type', 'debtor')->where('amount', '<', 0)->sum(fn($item) => doubleval($item instanceof stdClass ? $item->amount : $item['amount']));

        // creditor sum  before start period search
        $positive_creditor_root_open = $openedDebtorEntries->where('account_master_type', 'creditor')->where('amount', '>', 0)->sum(fn($item) => doubleval($item instanceof stdClass ? $item->amount : $item['amount']));

        $negative_creditor_root_open = $openedDebtorEntries->where('account_master_type', 'creditor')->where('amount', '<', 0)->sum(fn($item) => doubleval($item instanceof stdClass ? $item->amount : $item['amount']));


        $creditor_root_open = $positive_creditor_root_open + $negative_debtor_root_open * (-1);
        $debtor_root_open = $positive_debtor_root_open + $negative_creditor_root_open * (-1);

        $all_masters = $accountStatementService->getAccountMaster()->whereNull('parent_acct_no');

        $ac_cost_cen_branches = $accountStatementService->getCostBranches()->sortByDesc('id')->pluck('cost_description', 'id')->toArray();
        $ac_cost_cen_field_adds = $accountStatementService->getCostCenFieldAdd()->sortByDesc('id')->pluck('cost_description', 'id')->toArray();
        $branch_cost_center = $request->input('branch_cost_center');
        $field_cost_center = $request->input('field_cost_center');

        $menuItems = $request->menuItems;
        return view(
            'ac_reports.trial_balance',
            compact(
                'all_masters',
                'accountStatementService',
                'creditor_root_open',
                'debtor_root_open',
                'creditor_root_main',
                'debtor_root_main',
                'previous_date',
                'date_from',
                'date_to',
                'branch_cost_center',
                'field_cost_center',
                'ac_cost_cen_branches',
                'ac_cost_cen_field_adds',
                'menuItems'
            )
        );
    }

    public function IncomeStatementReport(Request $request)
    {
        $this->authorize('income_statement.access');

        $first_day = Carbon::now()->startOfYear()->format('Y-m-d');
        $now_date = Carbon::now()->endOfYear()->format('Y-m-d');

        $date_from = isset($request->ar_date_from) ? date('Y-m-d', strtotime($request->ar_date_from)) : $first_day;
        $date_to = isset($request->ar_date_to) ? date('Y-m-d', strtotime($request->ar_date_to)) : $now_date;
        $branch_cost_center = $request->input('branch_cost_center');
        $field_cost_center = $request->input('field_cost_center');

        $previous_date = Carbon::parse($date_from)->subDays(1)->format('Y-m-d');

        $accountStatementService = new AccountStatementService;

        $ac_setting = AcSetting::firstOrFail();

        $operating_income_41 = $accountStatementService->getAccountMaster()->where('account_number', $ac_setting->operating_income)->first();
        $direct_expenses_51 = $accountStatementService->getAccountMaster()->where('account_number', $ac_setting->direct_expenses)->first();
        $non_operating_income_42 = $accountStatementService->getAccountMaster()->where('account_number', $ac_setting->non_operating_income)->first();
        $operating_expenses_52 = $accountStatementService->getAccountMaster()->where('account_number', $ac_setting->operating_expenses)->first();
        $non_operating_expenses_53 = $accountStatementService->getAccountMaster()->where('account_number', $ac_setting->non_operating_expenses)->first();
        $sales_return = $accountStatementService->getAccountMaster()->where('account_number', $ac_setting->sales_return)->first();


        $ac_cost_cen_branches = $accountStatementService->getCostBranches()->sortByDesc('id')->pluck('cost_description', 'id')->toArray();
        $ac_cost_cen_field_adds = $accountStatementService->getCostCenFieldAdd()->sortByDesc('id')->pluck('cost_description', 'id')->toArray();

        $menuItems = $request->menuItems;
        return view(
            'ac_reports.income_statement',
            compact(
                'accountStatementService',
                'operating_income_41',
                'direct_expenses_51',
                'non_operating_income_42',
                'operating_expenses_52',
                'non_operating_expenses_53',
                'sales_return',
                'previous_date',
                'date_from',
                'date_to',
                'branch_cost_center',
                'field_cost_center',
                'ac_cost_cen_branches',
                'ac_cost_cen_field_adds',
                'menuItems'
            )
        );
    }

    public function BalanceSheetReport(Request $request)
    {
        $this->authorize('balance_sheet.access');

        $first_day = now()->startOfYear()->format('Y-m-d');
        $accountStatementService = new AccountStatementService;

        $now_date = now()->format('Y-m-d');
        $date_from = $first_day;
        $date_to = isset($request->ar_date_to) ? date('Y-m-d', strtotime($request->ar_date_to)) : $now_date;
        $previous_date = Carbon::parse($first_day)->subDays(1)->format('Y-m-d');
        // dd($previous_date);

        $branch_cost_center = $request->input('branch_cost_center');
        $field_cost_center = $request->input('field_cost_center');

        $business_id = request()->session()->get('user.business_id');
        $ac_setting = AcSetting::firstOrFail();
        $accountsMasters = $accountStatementService->getAccountMaster();

        $acMasters = $accountsMasters->whereIn('account_number', array_values($ac_setting->only('assets', 'liabilities', 'equity', 'current_period_profit_loss', 'operating_income', 'direct_expenses', 'non_operating_expenses', 'operating_expenses', 'non_operating_income')));

        $assets_1 = $acMasters->where('account_number', $ac_setting->assets)->first();
        $liabilities_2 = $acMasters->where('account_number', $ac_setting->liabilities)->first();
        $equity_3 = $acMasters->where('account_number', $ac_setting->equity)->first();

        $profits_losses_current_period_acc = $ac_setting->current_period_profit_loss;
        $profits_losses_current_period = 0;

        $operating_income_41 = $ac_setting->operating_income;
        $direct_expenses_51 = $ac_setting->direct_expenses;
        $non_operating_income_42 = $ac_setting->non_operating_income;
        $operating_expenses_52 = $ac_setting->operating_expenses;
        $non_operating_expenses_53 = $ac_setting->non_operating_expenses;

        $array_of_cost_center = [];
        $branch_cost_centers = [];
        $field_cost_centers = [];

        if ($branch_cost_center) {
            $branch_cost_centers = $accountStatementService->getSelfCostCenterBranchParents($branch_cost_center);
        }

        if ($field_cost_center) {
            $field_cost_centers = $accountStatementService->getSelfCostCenterFieldsParents($request->input('field_cost_center'));
        }

        $total_operating_income_41 = $this->calNetAcc_val($operating_income_41, $date_from, $date_to, $previous_date, $branch_cost_centers, $field_cost_centers, $accountStatementService);
        $total_direct_expenses_51 = $this->calNetAcc_val($direct_expenses_51, $date_from, $date_to, $previous_date, $branch_cost_centers, $field_cost_centers, $accountStatementService);
        $total_non_operating_income_42 = $this->calNetAcc_val($non_operating_income_42, $date_from, $date_to, $previous_date, $branch_cost_centers, $field_cost_centers, $accountStatementService);
        $total_operating_expenses_52 = $this->calNetAcc_val($operating_expenses_52, $date_from, $date_to, $previous_date, $branch_cost_centers, $field_cost_centers, $accountStatementService);
        $total_non_operating_expenses_53 = $this->calNetAcc_val($non_operating_expenses_53, $date_from, $date_to, $previous_date, $branch_cost_centers, $field_cost_centers, $accountStatementService);

        $profits_losses_current_period = $total_operating_income_41 - $total_direct_expenses_51 + $total_non_operating_income_42 - $total_operating_expenses_52 - $total_non_operating_expenses_53;

        $ac_cost_cen_branches = $accountStatementService->getCostBranches()->sortByDesc('id')->pluck('cost_description', 'id')->toArray();
        $ac_cost_cen_field_adds = $accountStatementService->getCostCenFieldAdd()->sortByDesc('id')->pluck('cost_description', 'id')->toArray();

        $menuItems = $request->menuItems;
        return view(
            'ac_reports.balance_sheet',
            compact(
                'assets_1',
                'accountsMasters',
                'accountStatementService',
                'liabilities_2',
                'branch_cost_centers',
                'field_cost_centers',
                'equity_3',
                'profits_losses_current_period',
                'profits_losses_current_period_acc',

                'previous_date',
                'date_from',
                'date_to',

                'branch_cost_center',
                'field_cost_center',
                'ac_cost_cen_branches',
                'ac_cost_cen_field_adds',
                'menuItems'
            )
        );
    }

    public function calNetAcc_val($target_account, $date_from, $date_to, $previous_date, $branch_cost_centers, $field_cost_centers, $accountStatementService)
    {
        $total = 0;
        $array_of_acc = $accountStatementService->getParents($target_account, true);

        foreach ($array_of_acc as $account) {
            $debtor_main = 0;
            $creditor_main = 0;
            $debtor_open = 0;
            $creditor_open = 0;
            $net_open = 0;
            $net_main = 0;
            $main_acc_type = '';
            $open_acc_type = '';
            $net_close = 0;
            $net_debtor_close = 0;
            $net_creditor_close = 0;
            $net_acc_type = '';
            $account_details = $accountStatementService->getAccountMaster()->where('account_number', $account)->first();
            $positive_main = $accountStatementService->closedEntriesData($date_from, $date_to, $branch_cost_centers, $field_cost_centers)
                ->where('account_number', $account)
                ->where('amount', '>', 0)
                ->sum('amount');
            $negative_main = $accountStatementService->closedEntriesData($date_from, $date_to, $branch_cost_centers, $field_cost_centers)->where('account_number', $account)
                ->where('amount', '<', 0)
                ->sum('amount');
            //for before start period search (open)
            $positive_open = $accountStatementService->openedEntriesData($previous_date, $field_cost_centers, $branch_cost_centers)
                ->where('account_number', $account)
                ->where('amount', '>', 0)
                ->sum('amount');
            $negative_open = $accountStatementService->openedEntriesData($previous_date, $field_cost_centers, $branch_cost_centers)
                ->where('account_number', $account)
                ->where('amount', '<', 0)
                ->sum('amount');
            //debtor
            if ($account_details->account_type == 'debtor') {
                $debtor_main = $positive_main;
                $creditor_main = $negative_main * -1;

                $debtor_open = $positive_open;
                $creditor_open = $negative_open * -1;


                $net_debtor_close = $debtor_open + $debtor_main;
                $net_creditor_close = $creditor_open + $creditor_main;
                if ($net_debtor_close >= $net_creditor_close) {
                    $net_close = $net_debtor_close - $net_creditor_close;
                    $net_acc_type = 'debtor';
                } else {
                    $net_close = $net_creditor_close - $net_debtor_close;
                    $net_acc_type = 'creditor';
                }
            } else {
                //creditor
                $creditor_main = $positive_main;
                $debtor_main = $negative_main * -1;

                $creditor_open = $positive_open;
                $debtor_open = $negative_open * -1;

                $net_debtor_close = $debtor_open + $debtor_main;
                $net_creditor_close = $creditor_open + $creditor_main;
                if ($net_creditor_close >= $net_debtor_close) {
                    $net_close = $net_creditor_close - $net_debtor_close;
                    $net_acc_type = 'creditor';
                } else {
                    $net_close = $net_debtor_close - $net_creditor_close;
                    $net_acc_type = 'debtor';
                }
            }

            if ($net_acc_type == $account_details->account_type) {
                $total += $net_close;
            } else {
                $total -= $net_close;
            }
        }

        return $total;
    }

    public function getFilterTypeDataList(Request $request)
    {

        $output = [];

        if (request()->ajax()) {
            $business_id = request()->session()->get('user.business_id');
            $type = $request->type;
            if ($type == "branch_cost_center") {
                $ac_cost_cen_list = AcCostCenBranche::userHaveAccess()->where('business_id', $business_id)->orderBy('id', 'desc')->pluck('cost_description', 'id')->toArray();
            } elseif ($type == "feild_add_cost_center") {
                $ac_cost_cen_list = AcCostCenFieldAdd::where('business_id', $business_id)->orderBy('id', 'desc')->pluck('cost_description', 'id')->toArray();
            }
            $output['success'] = true;
            $output['msg'] = __('lang_v1.done');
            $output['html_content'] = view('ac_reports.includes.cost_center_drop_down_list')
                ->with(compact('ac_cost_cen_list'))->render();
        } else {
            $output['success'] = false;
            $output['msg'] = __('lang_v1.xxx');
        }


        return $output;
    }
    /**
     * @param Request $request
     * @param $type
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    protected function accountStatementReportService($request, $type, $parentId, $viewPath)
    {
        $this->authorize('account_statement.access');
        $business_id = request()->session()->get('user.business_id');
        $accountStatementService = new AccountStatementService;
        $first_day = now()->startOfYear()->format('Y-m-d');
        $now_date = now()->endOfYear()->format('Y-m-d');
        // $now_date = date('Y-m-d');
        // dd($request->all());
        $date_from = isset($request->ar_date_from) ? date('Y-m-d', strtotime($request->ar_date_from)) : $first_day;
        $date_to = isset($request->ar_date_to) ? date('Y-m-d', strtotime($request->ar_date_to)) : $now_date;

        $previous_date = Carbon::parse($date_from)->subDays(1)->format('Y-m-d');

        $filter_type = isset($request->filter_type) ? $request->filter_type : NULL;
        $cost_center = isset($request->cost_center) ? $request->cost_center : NULL;

        $journal_entries = null;
        $account_number = isset($request->account_number) ? $request->account_number : '';
        $int_sec_acc_number = isset($request->int_sec_acc_number) ? $request->int_sec_acc_number : '';
        $filter_from_acc = isset($request->filter_from_acc) ? $request->filter_from_acc : '';
        $filter_to_acc = isset($request->filter_to_acc) ? $request->filter_to_acc : '';
        $accounts_range = [];

        $filter_from_acc = '';
        $filter_to_acc = '';
        $branch_cost_centers = [];
        $field_cost_centers = [];
        $branch_cost_center = $request->input('branch_cost_center');
        $field_cost_center = $request->input('field_cost_center');
        $accounts_range = request('account_number', []);

        if ($request->filled('branch_cost_center')) {
            $branch_cost_centers = $accountStatementService->getSelfCostCenterBranchParents($branch_cost_center);
        }
        if ($request->filled('field_cost_center')) {
            $field_cost_centers = $accountStatementService->getSelfCostCenterFieldsParents($field_cost_center);
        }
        $journal_entries = AcJournalEntryDetail::whereIn('account_number', $accounts_range)
            ->forJournalEntry([$date_from, $date_to], $branch_cost_centers)
            ->forAccountType($field_cost_centers)
            ->with('ac_journal_entry', 'account_type')
            ->get()
            ->groupBy('account_number');


        if ($journal_entries) {
            $journal_entries = $journal_entries->reverse();
        }

        $lastChildrenBranch = AcMaster::whereDoesntHave('parents')
            ->where('account_type', $type)
            ->where('parent_acct_no', $parentId)
            ->selectRaw("account_number, concat(account_name_ar, ' (', account_number , ')') as account_name_number")
            ->pluck('account_name_number', 'account_number')->toArray();

        $accountsMaster = AcMaster::whereIn('account_number', $accounts_range)->get();
        // branch_cost_center
        $ac_cost_cen_branches = AcCostCenBranche::userHaveAccess()->where('business_id', $business_id)->orderBy('id', 'desc')->pluck('cost_description', 'id')->toArray();
        $ac_cost_cen_field_adds = AcCostCenFieldAdd::where('business_id', $business_id)->orderBy('id', 'desc')->pluck('cost_description', 'id')->toArray();
        $menuItems = $request->menuItems;
        return view($viewPath, get_defined_vars(), compact('menuItems'));
    }
}
