<?php

namespace App\Http\Controllers;

use App\BusinessLocation;
use App\Models\AcJournalEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class OpeningEntryController extends Controller
{
    //
    public function index(Request $request)
    {

        // dd($request->all());
        $business_id = request()->session()->get('user.business_id');
        $business_locations = BusinessLocation::forDropdown($business_id, false);

        $entryType = "opening";

        if (request()->ajax()) {
            $business_id = request()->session()->get('user.business_id');

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
        }

        $type = "";
        if (request()->has('type')) {
            $type = $request->input('type');
        }

        $menuItems = $request->menuItems;
        return view('ac_journal_entry.index', compact('entryType', 'type', 'menuItems'));
    }
}
