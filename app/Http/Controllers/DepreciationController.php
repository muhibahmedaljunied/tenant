<?php

namespace App\Http\Controllers;

use App\Models\AcAsset;
use App\Models\AcAssetClass;
use App\Models\AcJournalEntry;
use App\Models\AcJournalEntryDetail;
use App\Models\Depreciation;
use App\Models\Payment;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterval;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DepreciationController extends Controller
{
    public function index(Request $request) {
        if (request()->ajax()) {
            $roles = Depreciation::select('id', 'reference_number', 'from', 'to', 'depreciation_period');

            return DataTables::of($roles)
                ->editColumn('from', function(Depreciation $depreciation){
                    switch ($depreciation->depreciation_period){
                        case 'weekly':
                            $date = CarbonImmutable::create($depreciation->from_year)->week($depreciation->from - 1)->addDays(7);
                            break;
                        case 'monthly':
                            $date = CarbonImmutable::create($depreciation->from_year)->month($depreciation->from)->endOfMonth();
                            break;
                        case 'yearly':
                            $date = CarbonImmutable::create($depreciation->from_year)->lastOfYear();
                            break;
                    }
                    return $date->format('d-m-Y');
                })
                ->editColumn('to', function(Depreciation $depreciation){
                    switch ($depreciation->depreciation_period){
                        case 'weekly':
                            $date = CarbonImmutable::create($depreciation->to_year)->week($depreciation->to - 1)->addDays(7);
                            break;
                        case 'monthly':
                            $date = CarbonImmutable::create($depreciation->to_year)->month($depreciation->to)->endOfMonth();
                            break;
                        case 'yearly':
                            $date = CarbonImmutable::create($depreciation->to_year)->lastOfYear();
                            break;
                    }
                    return $date->format('d-m-Y');
                })
                ->addColumn('action', function ($row) {
                    $action = '<a href="' . action('DepreciationController@show', ['depreciation' => $row->id]) . '" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-eye-open"></i> ' . __("messages.view") . '</a>';
                    $action .= '<button data-href="' . action('DepreciationController@destroy', ['depreciation' => $row->id]) . '" class="btn btn-xs btn-danger delete_role_button mr-8"><i class="glyphicon glyphicon-trash"></i> ' . __("messages.delete") . '</button>';
                    return $action;
                })
                ->removeColumn('id')
                ->removeColumn('depreciation_period')
                ->rawColumns([3])
                ->make(false);
        }

        $menuItems = $request->menuItems;
        return view('depreciations.index',compact('menuItems'));
    }
    public function create(Request $request)
    {
        $business_id = request()->session()->get('user.business_id');
        $assets = AcAssetClass::where('business_id', $business_id)->where('is_depreciable', 1)->get()->pluck('asset_class_name_ar', 'id');
        $depreciation = Depreciation::max('reference_number');
        preg_match('/[0-9]+/', $depreciation, $matches);
        $reference_number = count($matches) ? (int)$matches[0] : 0;
        $ref_num = "DE1";
        if ($depreciation) {
            $ref_num = "DE" . ($reference_number + 1);
        }

         $menuItems = $request->menuItems;
        return view('depreciations.create', compact('assets', "ref_num",'menuItems'));
    }
    //
    public function store(Request $request)
    {
        $asset = AcAsset::find($request->input('asset_id'));
        $assetClass = AcAssetClass::find($request->input('asset_class_id'));
        $day_deprecate = $asset->asset_value / ($assetClass->useful_life * 365);
        /*$request->validate([
            'asset_class' => 'required',
            'asset' => 'required',
            'start_year' => 'required',
            'depreciation_period' => 'required',
        ]);*/
        Depreciation::create($request->except('_token'));
        if ($request->input('depreciation_period') === 'weekly') {
            $dtStart = CarbonImmutable::create($request->input('from_year'))->week($request->input('from') - 1)->addDays(7);
            $dtEnd = CarbonImmutable::create($request->input('to_year'))->week($request->input('to') - 1)->addDays(7);
            $periods = CarbonPeriod::create($dtStart, '7 days', $dtEnd);
            $amount = $day_deprecate * 7;
        } else if($request->input('depreciation_period') === 'monthly') {
            $dtStart = CarbonImmutable::create($request->input('from_year'))->month($request->input('from'))->endOfMonth();
            $dtEnd = CarbonImmutable::create($request->input('to_year'))->month($request->input('to'))->endOfMonth();
            $periods = [$dtStart];
            $notFound = true;
            /** @var Carbon $target */
            $target = Carbon::createFromImmutable($dtStart);
            while ($notFound){
                $periods[] = $target->addDay()->endOfMonth()->toImmutable();
                if($target->equalTo($dtEnd)) {
                    $notFound = false;
                }
            }
            $amount = $day_deprecate * 30;
        } else {
            $dtStart = CarbonImmutable::create($request->input('from_year'))->lastOfYear();
            $dtEnd = CarbonImmutable::create($request->input('to_year'))->lastOfYear();
            $periods = CarbonPeriod::create($dtStart, '1 year', $dtEnd);
            $amount = $day_deprecate * 365;
        }
        $period_name = $request->input('depreciation_period') === 'weekly' ? 'إسبوع' : ($request->input("depreciation_period") === "monthly" ? "شهر" : "سنة");
        foreach($periods as $period){
            $journalEntry = AcJournalEntry::create([
                    'entry_no' => 1,
                    'entry_date' => $period,
                    'entry_desc' => "إهلاك {$asset->asset_name_ar} لمدة {$period_name} بتاريخ {$period->format('d-m-Y')}",
                    "entry_type" => "depreciation"
                ]);
                AcJournalEntryDetail::create([
                    'account_number' =>  $asset->asset_expense_account,
                    'amount' => $amount,
                    'ac_journal_entries_id' => $journalEntry->id,
                ]);
                AcJournalEntryDetail::create([
                    'account_number' =>$asset->accumulated_consumption_account,
                    'amount' => $amount,
                    'ac_journal_entries_id' => $journalEntry->id
                ]);
        }
        return redirect()->action('DepreciationController@index');
    }


    public function show(Depreciation $depreciation){
        return view('depreciations.show', compact('depreciation'));
    }

    public function destroy(Depreciation $depreciation){
        $depreciation->delete();
        return back();
    }

}
