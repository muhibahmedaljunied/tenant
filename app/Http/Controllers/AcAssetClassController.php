<?php

namespace App\Http\Controllers;

use App\Models\AcAssetClass;
use App\Models\AcMaster;
use App\Utils\Util;
use Illuminate\Http\Request;
use Kindy\EgyaptianEInvoice\ETAInvoice;
use Yajra\DataTables\Facades\DataTables;

class AcAssetClassController extends Controller
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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
   
        // $invoice = new ETAInvoice('','', 'uat');

        if (request()->ajax()) {
            $business_id = request()->session()->get('user.business_id');

            $asset_classes = AcAssetClass::with('asset_account_details', 'asset_expense_account_details', 'accumulated_consumption_account_details')->where('business_id', $business_id)->orderBy('id', 'desc')->get();
            // dd($asset_classes);
            $datatable =  DataTables::of($asset_classes)
                ->addColumn(
                    'action',
                    '
                        @can("customer.update")
                                <a  target="_blank" href="{{action(\'AcAssetClassController@edit\', [$id])}} " class="btn btn-xs btn-primary"><i class="fas fa-edit"></i>  @lang("messages.edit") </a>
                        @endcan
                     
                        @can("customer.delete")
                            <button data-href="{{action(\'AcAssetClassController@destroy\', [$id])}}" class="btn btn-xs btn-danger delete_ac_asset_class_button"><i class="glyphicon glyphicon-trash"></i> @lang("messages.delete")</button>
                        @endcan'
                )
                ->editColumn('asset_class_name', ' {{ $asset_class_name_ar }} ')
                ->editColumn('asset_account', function ($row) {
                    if ($row->asset_account) {
                        return $row->asset_account_details->account_name_ar;
                    } else {
                        return __("assets.is_main");
                    }
                })
                ->editColumn('asset_expense_account', function ($row) {
                    if ($row->asset_expense_account) {
                        return $row->asset_expense_account_details->account_name_ar;
                    } else {
                        return __("assets.empty");
                    }
                })
                ->editColumn('accumulated_consumption_account', function ($row) {
                    if ($row->accumulated_consumption_account) {
                        return $row->accumulated_consumption_account_details->account_name_ar;
                    } else {
                        return __("assets.empty");
                    }
                })
                ->editColumn('is_depreciable', function ($row) {
                    if ($row->is_depreciable == 1) {
                        return __("assets.yes");
                    } else {
                        return __("assets.no");
                    }
                })
                ->editColumn('useful_life_type', function ($row) {
                    if ($row->is_depreciable == 1) {
                        if ($row->useful_life_type == 'years') {
                            return '( ' . $row->useful_life . ' )' . __("assets.years");
                        } else {
                            return '( ' . $row->useful_life . ' )' . __("assets.percent");
                        }
                    } else {
                        return __("assets.empty");
                    }
                })
                ->editColumn('row_no', '{{$id}} ');
            $rawColumns = ['action', 'asset_class_name', 'asset_account', 'is_depreciable', 'asset_expense_account', 'accumulated_consumption_account', 'useful_life_type', 'row_no'];

            return $datatable->rawColumns($rawColumns)
                ->make(true);
        }

        $menuItems = $request->menuItems;
        return view('ac_asset_class.index',compact('menuItems'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $business_id = request()->session()->get('user.business_id');
        $lastChildrenBranch = AcMaster::whereDoesntHave('parents')->selectRaw("account_number, concat(account_name_ar, ' (', account_number , ')') as account_name_number")->pluck('account_name_number', 'account_number')->toArray();
              $menuItems = $request->menuItems;
        return view('ac_asset_class.create')
            ->with(compact('lastChildrenBranch','menuItems'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->except('_token', 'submit_type');
        // dd($request->all());
        try {
            $asset_class['asset_class_name_ar'] = $input['asset_class_name_ar'];
            $asset_class['asset_class_name_en'] = $input['asset_class_name_en'];
            $asset_class['asset_account'] = $input['asset_account'];
            if (isset($input['is_depreciable'])) {
                $asset_class['is_depreciable'] = 1;
                $asset_class['asset_expense_account'] = $input['asset_expense_account'];
                $asset_class['accumulated_consumption_account'] = $input['accumulated_consumption_account'];
                $asset_class['useful_life_type'] = $input['useful_life_type'];
                $asset_class['useful_life'] = $input['useful_life'];
            }
            $asset_class_record = AcAssetClass::create($asset_class);
            $output = [
                'success' => true,
                'data' => $input,
                'msg' => __("lang_v1.success")
            ];
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

            $output = [
                'success' => false,
                'msg' => __("messages.something_went_wrong")
            ];
        }
        return redirect()
            ->action('AcAssetClassController@index')
            ->with('status', $output);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $business_id = request()->session()->get('user.business_id');
        $asset_class_details = AcAssetClass::where('business_id', $business_id)->find($id);
        $lastChildrenBranch = AcMaster::whereDoesntHave('parents')->selectRaw("account_number, concat(account_name_ar, ' (', account_number , ')') as account_name_number")->pluck('account_name_number', 'account_number')->toArray();
        return view('ac_asset_class.edite')
            ->with(compact('asset_class_details', 'lastChildrenBranch'));
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
        $input = $request->except('_token', 'submit_type');
        // dd($request->all());
        try {

            $asset_class_record = AcAssetClass::findOrFail($id);
            $input_update['asset_class_name_ar'] = $input['asset_class_name_ar'];
            $input_update['asset_class_name_en'] = $input['asset_class_name_en'];
            $input_update['asset_account'] = $input['asset_account'];
            if (isset($input['is_depreciable'])) {
                $input_update['is_depreciable'] = 1;
                $input_update['asset_expense_account'] = $input['asset_expense_account'];
                $input_update['accumulated_consumption_account'] = $input['accumulated_consumption_account'];
                $input_update['useful_life_type'] = $input['useful_life_type'];
                $input_update['useful_life'] = $input['useful_life'];
            }else{
                $input_update['is_depreciable'] = 0;
                $input_update['asset_expense_account'] = null;
                $input_update['accumulated_consumption_account'] = null;
                $input_update['useful_life_type'] = 'years';
                $input_update['useful_life'] = '';
            }
            $asset_class_record->update($input_update);

            $output = [
                'success' => true,
                'data' => $input,
                'msg' => __("lang_v1.success")
            ];
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

            $output = [
                'success' => false,
                'msg' => __("messages.something_went_wrong")
            ];
        }
        return redirect()
            ->action('AcAssetClassController@index')
            ->with('status', $output);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (request()->ajax()) {
            try {
                // $business_id = request()->user()->business_id;

                $ac_asset_class_record = AcAssetClass::findOrFail($id);
                $ac_asset_class_record->delete();

                $output = [
                    'success' => true,
                    'msg' => __("lang_v1.success")
                ];
            } catch (\Exception $e) {
                \Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

                $output = [
                    'success' => false,
                    'msg' => __("messages.something_went_wrong")
                ];
            }

            return $output;
        }
    }
}
