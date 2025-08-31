<?php

namespace App\Http\Controllers;

use App\Models\AcCostCenFieldAdd;
use App\Models\User;
use App\Utils\Util;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AcCostCenFieldAddController extends Controller
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

        $this->authorize('extra_cost_center.view');
        if (request()->ajax()) {
            $business_id = request()->session()->get('user.business_id');

            $ac_cost_cen_field_add = AcCostCenFieldAdd::with('details')->where('business_id', $business_id)->orderBy('id', 'desc')->get();
            // dd($ac_cost_cen_field_add);
            $datatable =  DataTables::of($ac_cost_cen_field_add)
                ->addColumn(
                    'action',
                    '
                        @can("extra_cost_center.edit")
                                <a  target="_blank" href="{{action(\'AcCostCenFieldAddController@edit\', [$id])}} " class="btn btn-xs btn-primary"><i class="fas fa-edit"></i>  @lang("messages.edit") </a>
                        @endcan
                     
                        @can("extra_cost_center.delete")
                            <button data-href="{{action(\'AcCostCenFieldAddController@destroy\', [$id])}}" class="btn btn-xs btn-danger delete_ac_journal_entry_button"><i class="glyphicon glyphicon-trash"></i> @lang("messages.delete")</button>
                        @endcan'
                )
                ->editColumn('cost_description', ' {{ $cost_description }} ')
                // ->editColumn('parent_cost_no', '@if($parent_cost_no) {{$details->cost_description}} @else "is a main" @endif ')
                ->editColumn('parent_cost_no', function ($row) {
                    if ($row->parent_cost_no) {
                        return $row->details->cost_description;
                    } else {
                        return __("chart_of_accounts.is_main");
                    }
                })
                ->editColumn('row_no', '{{$sequence}} ');
            $rawColumns = [ 'cost_description', 'parent_cost_no', 'row_no'];
            if(auth()->user()->can('extra_cost_center.edit') || auth()->user()->can('extra_cost_center.delete')) {
                $rawColumns[] = 'action';
            }
            return $datatable->rawColumns($rawColumns)
                ->make(true);
        }

        $menuItems = $request->menuItems;
        return view('ac_cost_cen_field_add.index',compact('menuItems'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('extra_cost_center.create');
        $business_id = request()->session()->get('user.business_id');
        $ac_cost_cen_field_adds = AcCostCenFieldAdd::where('business_id', $business_id)->pluck('cost_description','id')->toArray();
        $users = User::forDropdown($business_id, false);


        $menuItems = $request->menuItems;
        return view('ac_cost_cen_field_add.create')
            ->with(compact('ac_cost_cen_field_adds', 'users','menuItems'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('extra_cost_center.create');
        $input = $request->except('_token', 'submit_type');
        $business_id = $request->session()->get('user.business_id');
        // dd($request->all());
        try {

            $cost_center['cost_description'] = $input['cost_description'];
            $cost_center['parent_cost_no'] = $input['parent_cost_no'];
            if($input['parent_cost_no']){
                $cost_center_details = AcCostCenFieldAdd::findOrFail($input['parent_cost_no']);
                $cost_center['cost_level'] = $cost_center_details->cost_level + 1;
            }else{
                $cost_center['cost_level'] = 1;
            }
            $cost_center['business_id'] = $business_id;
            $cost_center_record = AcCostCenFieldAdd::create($cost_center);
            $users = $input['users'];
            if (!empty($users)) {
                $cost_center_record->ac_cost_cen_field_adds()->sync($users);
            }


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
            ->action('AcCostCenFieldAddController@index')
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
        $this->authorize('extra_cost_center.edit');
        $business_id = request()->session()->get('user.business_id');
        $ac_cost_details = AcCostCenFieldAdd::where('business_id', $business_id)->find($id);
        $ac_cost_cen_field_adds = AcCostCenFieldAdd::where('business_id', $business_id)->pluck('cost_description','id')->toArray();
        $users = User::forDropdown($business_id, false);
        return view('ac_cost_cen_field_add.edite')
            ->with(compact('ac_cost_details','ac_cost_cen_field_adds', 'users'));
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
        $this->authorize('extra_cost_center.edit');
        $input = $request->except('_token', 'submit_type');
        // dd($request->all());
        try {

            $cost_center_record = AcCostCenFieldAdd::findOrFail($id);
            $input_update['cost_description'] = $input['cost_description'];
            $input_update['parent_cost_no'] = $input['parent_cost_no'];
            if($input['parent_cost_no']){
                $cost_center_details = AcCostCenFieldAdd::findOrFail($input['parent_cost_no']);
                $input_update['cost_level'] = $cost_center_details->cost_level + 1;
            }else{
                $input_update['cost_level'] = 1;
            }
            $cost_center_record->update($input_update);
            $users = $input['users'];
            if (!empty($users)) {
                $cost_center_record->ac_cost_cen_field_adds()->sync($users);
            }


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
            ->action('AcCostCenFieldAddController@index')
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
        $this->authorize('extra_cost_center.delete');
        if (request()->ajax()) {
            try {
                // $business_id = request()->user()->business_id;

                $ac_journal_entry_record = AcCostCenFieldAdd::findOrFail($id);
                $ac_journal_entry_record->delete();

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
