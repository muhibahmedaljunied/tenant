<?php

namespace App\Http\Controllers;

use App\BusinessLocation;
use App\CustomerGroup;
use App\Models\AcJournalEntry;
use App\Utils\ContactUtil;
use App\Utils\ModuleUtil;
use App\Utils\NotificationUtil;
use App\Utils\TransactionUtil;
use App\Utils\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Modules\Tracker\Entities\Track;
use PSpell\Config;
use Yajra\DataTables\Facades\DataTables;

class PeriodController extends Controller
{
    //

    protected $commonUtil;
    protected $contactUtil;
    protected $transactionUtil;
    protected $moduleUtil;
    protected $notificationUtil;

    /**
     * Constructor
     *
     * @param Util $commonUtil
     * @return void
     */
    public function __construct(
        Util             $commonUtil,
        ModuleUtil       $moduleUtil,
        TransactionUtil  $transactionUtil,
        NotificationUtil $notificationUtil,
        ContactUtil      $contactUtil
    ) {
        $this->commonUtil = $commonUtil;
        $this->contactUtil = $contactUtil;
        $this->moduleUtil = $moduleUtil;
        $this->transactionUtil = $transactionUtil;
        $this->notificationUtil = $notificationUtil;
    }


    public function index(Request $request)
    {

        // dd($request->all());
        $business_id = request()->session()->get('user.business_id');
        $business_locations = BusinessLocation::forDropdown($business_id, false);

        $entryType = "opening";

        if (request()->ajax()) {
            $business_id = request()->session()->get('user.business_id');


            if ($request['type'] == 'account') {


                // --------------------------------------------------------------------------------------------------
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
                    ->where('entry_type', 'opening')
                    ->orderBy('id', 'desc');



                if (request()->filled('type')) {
                    $ac_journal_entries->where('type', request()->input('type'));
                }
                // dd($ac_journal_entries);
                // return $ac_journal_entries;
                $datatable = DataTables::of($ac_journal_entries->get())
                    ->addColumn(
                        'action',
                        '
                    @can("customer.update")
                        @if($entry_type=="daily")
                            <a  target="_blank" href="{{action(\'AcJournalEntryController@edit\', [$id])}} " class="btn btn-xs btn-primary"><i class="fas fa-edit"></i>  @lang("messages.edit") </a>
                        @else
                            <a  target="_blank" href="{{action(\'AcJournalEntryController@editOpenAccountEntry\', [$id])}} " class="btn btn-xs btn-primary"><i class="fas fa-edit"></i>  @lang("messages.edit") </a>
                        @endif
                    @endcan
                    @can("customer.view")
                    <a target="_blank" href="{{action(\'AcJournalEntryController@show\', [$id])}}" class="btn btn-xs btn-info"><i class="fa fa-eye"></i> @lang("messages.view")</a>
                    &nbsp;
                    @endcan
                    @can("customer.delete")
                        <button data-href="{{action(\'AcJournalEntryController@destroy\', [$id])}}" class="btn btn-xs btn-danger delete_ac_journal_entry_button"><i class="glyphicon glyphicon-trash"></i> @lang("messages.delete")</button>
                    @endcan'
                    )
                    ->editColumn('entry_desc', ' {{ $entry_desc }} ')
                    ->editColumn('entry_date', '@if($entry_date=="percentage") {{$entry_date}} @else {{$entry_date}} @endif ')
                    ->editColumn('entry_type', '@if($entry_type=="daily") @lang("chart_of_accounts.entry_type_daily") @else @lang("chart_of_accounts.entry_type_opening") @endif ')
                    ->editColumn('entry_no', '{{$sequence}} ')
                    ->editColumn('entry_lines_no', ' {{ $ac_journal_entries_count  }} ')
                    // ->editColumn('entry_total_term', '@if($ac_journal_entries_sum_amount >= 0) {{$ac_journal_entries_sum_amount/2}} @else {{($ac_journal_entries_sum_amount*-1)/2}} @endif ');
                    ->editColumn('entry_total_term', '@if(isset($ac_journal_entries_sum_amount_n)) {{DisplayDouble(($ac_journal_entries_sum_amount + $ac_journal_entries_sum_amount_n*-1)/2)}} @else {{DisplayDouble($ac_journal_entries_sum_amount/2)}} @endif ');

                $rawColumns = ['action', 'entry_desc', 'entry_no', 'entry_date', 'entry_type'];

                return $datatable->rawColumns($rawColumns)
                    ->make(true);

                // --------------------------------------------------------------------------------------------------



            }
            if ($request['type'] == 'product') {


                // --------------------------------------------------------------------------------------------------
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
                    ->where('entry_type', 'opening')
                    ->orderBy('id', 'desc');



                if (request()->filled('type')) {
                    $ac_journal_entries->where('type', request()->input('type'));
                }
                // dd($ac_journal_entries);
                // return $ac_journal_entries;
                $datatable = DataTables::of($ac_journal_entries->get())
                    ->addColumn(
                        'action',
                        '
                    @can("customer.update")
                        @if($entry_type=="daily")
                            <a  target="_blank" href="{{action(\'AcJournalEntryController@edit\', [$id])}} " class="btn btn-xs btn-primary"><i class="fas fa-edit"></i>  @lang("messages.edit") </a>
                        @else
                            <a  target="_blank" href="{{action(\'AcJournalEntryController@editOpenAccountEntry\', [$id])}} " class="btn btn-xs btn-primary"><i class="fas fa-edit"></i>  @lang("messages.edit") </a>
                        @endif
                    @endcan
                    @can("customer.view")
                    <a target="_blank" href="{{action(\'AcJournalEntryController@show\', [$id])}}" class="btn btn-xs btn-info"><i class="fa fa-eye"></i> @lang("messages.view")</a>
                    &nbsp;
                    @endcan
                    @can("customer.delete")
                        <button data-href="{{action(\'AcJournalEntryController@destroy\', [$id])}}" class="btn btn-xs btn-danger delete_ac_journal_entry_button"><i class="glyphicon glyphicon-trash"></i> @lang("messages.delete")</button>
                    @endcan'
                    )
                    ->editColumn('entry_desc', ' {{ $entry_desc }} ')
                    ->editColumn('entry_date', '@if($entry_date=="percentage") {{$entry_date}} @else {{$entry_date}} @endif ')
                    ->editColumn('entry_type', '@if($entry_type=="daily") @lang("chart_of_accounts.entry_type_daily") @else @lang("chart_of_accounts.entry_type_opening") @endif ')
                    ->editColumn('entry_no', '{{$sequence}} ')
                    ->editColumn('entry_lines_no', ' {{ $ac_journal_entries_count  }} ')
                    // ->editColumn('entry_total_term', '@if($ac_journal_entries_sum_amount >= 0) {{$ac_journal_entries_sum_amount/2}} @else {{($ac_journal_entries_sum_amount*-1)/2}} @endif ');
                    ->editColumn('entry_total_term', '@if(isset($ac_journal_entries_sum_amount_n)) {{DisplayDouble(($ac_journal_entries_sum_amount + $ac_journal_entries_sum_amount_n*-1)/2)}} @else {{DisplayDouble($ac_journal_entries_sum_amount/2)}} @endif ');

                $rawColumns = ['action', 'entry_desc', 'entry_no', 'entry_date', 'entry_type'];

                return $datatable->rawColumns($rawColumns)
                    ->make(true);

                // --------------------------------------------------------------------------------------------------


            }
        }

        $type = "";
        if (request()->has('type')) {
            $type = $request->input('type');
        }

        $menuItems = $request->menuItems;
        return view('period.index', compact(
            'entryType',
            'type',
            'menuItems'
        ));
    }


    public function open_period()
    {


        $business_id = request()->session()->get('user.business_id');


        $customer_groups = CustomerGroup::forDropdown($business_id);
        $selected_type = request()->type;

        $module_form_parts = $this->moduleUtil->getModuleData('contact_form_part');
        $isEnabledModuleTracker = $this->moduleUtil->isModuleInstalled('Tracker');
        $trackers = [];
        if ($isEnabledModuleTracker) {
            $trackers = Track::whereHas('user', function ($q) {
                $user = auth()->user();
                if (!$user->hasRole("Admin#{$user->business_id}")) {
                    $q->where('user_id', $user->id);
                }
            })->pluck('name', 'id');
        }
        $types['customer'] = __('report.customer');
        return view('period.open_period')
            ->with(compact(
                'types',
                'customer_groups',
                'isEnabledModuleTracker',
                'trackers',
                'selected_type',
                'module_form_parts'

            ));
    }


    public function close_period()
    {




        $business_id = request()->session()->get('user.business_id');

        $customer_groups = CustomerGroup::forDropdown($business_id);
        $selected_type = request()->type;

        $module_form_parts = $this->moduleUtil->getModuleData('contact_form_part');
        $isEnabledModuleTracker = $this->moduleUtil->isModuleInstalled('Tracker');
        $trackers = [];
        if ($isEnabledModuleTracker) {
            $trackers = Track::whereHas('user', function ($q) {
                $user = auth()->user();
                if (!$user->hasRole("Admin#{$user->business_id}")) {
                    $q->where('user_id', $user->id);
                }
            })->pluck('name', 'id');
        }
        $types['customer'] = __('report.customer');
        return view('period.close_period')
            ->with(compact(
                'types',
                'customer_groups',
                'isEnabledModuleTracker',
                'trackers',
                'selected_type',
                'module_form_parts'

            ));
    }



    // ---------------------------------------------------------------------------------------------------------

    public function create_new_year($year)
    {

        $new_database = env('DB_DATABASE') . $year;
        $old_database = env('DB_DATABASE_second');
        $path = base_path('.env');
        $test = file_get_contents($path);

        if (file_exists($path)) {
            file_put_contents($path, str_replace("DB_DATABASE_second=$old_database", "DB_DATABASE_second=$new_database", $test));
        }

        Config::set("database.connections.mysql2.database", env('DB_DATABASE_second', 'forge'));
        DB::connection('mysql')->statement("CREATE DATABASE $new_database");
    }

    public function init_new_year_data($data)
    {


        foreach ($data['data'] as $value) {

            $table = DB::select('select * from ' . $value);
            foreach ($table as $record) {
                DB::connection('mysql2')
                    ->table($value)
                    ->insert(get_object_vars($record));
            }
        }
    }



    public function create_account_year($request)
    {

        // dd($request);
        $account = new AccountYear();
        $account->name = $request['year'];
        $account->start_date = $request['start_date'];
        $account->end_date = $request['end_date'];
        $account->status = 0;
        $account->save();
    }


    public function store_account_period(Request $request)
    {


        $this->open_new_period($request);
        $this->copy_data_into_new_period($request);
        return response()->json('Year Created successfully');
    }


    public function open_new_period($request)
    {


        if ($request['year']) {

            $this->create_new_year($request['year']);
        }
        $this->create_account_year($request);
    }


    public function copy_data_into_new_period($request)
    {

         


        Artisan::call("migrate --database=mysql2");
        $this->init_new_year_data($request);
    }
}
