<?php

namespace App\Http\Controllers;

use App\Business;
use App\BusinessLocation;
use App\Store;
use App\Product;
use App\StoreDetail;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;

use App\Utils\Util;
use App\VariationLocationDetails;

class StoreController extends Controller
{
    /**
     * All Utils instance.
     *
     */
    protected $commonUtil;

    /**
     * Constructor
     *
     * @param ProductUtils $product
     * @return void
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
    // public function index(Request $request)
    // {
    //     if (! auth()->user()->can('store.view') && ! auth()->user()->can('store.create')) {
    //         abort(403, 'Unauthorized action.');
    //     }



    //     if (request()->ajax()) {

    //         dd(request()->ajax());
    //         $business_id = request()->session()->get('user.business_id');

    //         $store = StoreDetail::where('store_details.business_id', $business_id)
    //             ->join(
    //                 'stores',
    //                 'store_details.store_id',
    //                 '=',
    //                 'stores.id'
    //             )
    //             ->join(
    //                 'business_locations',
    //                 'store_details.location_id',
    //                 '=',
    //                 'business_locations.id'
    //             )
    //             ->select(
    //                 'stores.name as store_name',
    //                 'stores.code as short_name',
    //                 'business_locations.name as business_locations',

    //             )
    //             ->get();
    //         // $store = $store->get();

    //         // dd($store);

    //         return Datatables::of($store)
    //             ->addColumn(
    //                 'action',
    //                 '@can("store.update")
    //                 <button data-href="{{action(\'StoreController@edit\', [$id])}}" class="btn btn-xs btn-primary edit_store_button"><i class="glyphicon glyphicon-edit"></i> @lang("messages.edit")</button>
    //                     &nbsp;
    //                 @endcan
    //                 @can("store.delete")
    //                     <button data-href="{{action(\'StoreController@destroy\', [$id])}}" class="btn btn-xs btn-danger delete_store_button"><i class="glyphicon glyphicon-trash"></i> @lang("messages.delete")</button>
    //                 @endcan'
    //             )
    //             ->editColumn('store_name', function ($row) {
    //                 if ($row->store_name) {
    //                     return $row->store_name;
    //                 }
    //             })
    //             ->editColumn('short_name', function ($row) {
    //                 if (! empty($row->short_name)) {
    //                     return $row->short_name;
    //                 }
    //             })
    //             ->editColumn('business_locations', function ($row) {
    //                 if (! empty($row->business_locations)) {
    //                     return $row->business_locations;
    //                 }
    //             })
    //             ->removeColumn('id')
    //             ->rawColumns(['action'])
    //             ->make(true);
    //     }
    //     $menuItems = $request->menuItems;
    //     return view('store.index', compact('menuItems'));
    // }


    public function index(Request $request)
    {
        if (! auth()->user()->can('store.view') && ! auth()->user()->can('store.create')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            // dd(request()->ajax());
            $business_id = request()->session()->get('user.business_id');



            $store = Store::where('stores.business_id', $business_id)

                ->join(
                    'business_locations',
                    'stores.location_id',
                    '=',
                    'business_locations.id'
                )
                ->select(
                    'stores.id',
                    'stores.name as store_name',
                    'stores.code as short_name',
                    'business_locations.name as business_locations',

                )
                ->get();





            // dd($store);
            return Datatables::of($store)
                ->addColumn(
                    'action',
                    '@can("store.update")
                    <button data-href="{{action(\'StoreController@edit\', [$id])}}" class="btn btn-xs btn-primary edit_store_button"><i class="glyphicon glyphicon-edit"></i> @lang("messages.edit")</button>
                        &nbsp;
                    @endcan
                    @can("store.delete")
                        <button data-href="{{action(\'StoreController@destroy\', [$id])}}" class="btn btn-xs btn-danger delete_store_button"><i class="glyphicon glyphicon-trash"></i> @lang("messages.delete")</button>
                    @endcan'
                )
             
                ->editColumn('store_name', function ($row) {
                    if ($row->store_name) {
                        return $row->store_name;
                    }
                })
   
                ->editColumn('short_name', function ($row) {
                    if (! empty($row->short_name)) {
                        return $row->short_name;
                    }
                })
                ->editColumn('business_locations', function ($row) {
                    if (! empty($row->business_locations)) {
                        return $row->business_locations;
                    }
                })
                ->removeColumn('id')
                ->rawColumns(['action'])
                ->make(true);
        }
        $menuItems = $request->menuItems;
        return view('store.index', compact('menuItems'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! auth()->user()->can('store.create')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');

        $quick_add = false;
        if (! empty(request()->input('quick_add'))) {
            $quick_add = true;
        }

        $business_locations = BusinessLocation::forDropdown($business_id);

        return view('store.create')
            ->with(compact(
                'quick_add',
                'business_locations'
            ));
    }

    public function getStoresByLocations(Request $request)
    {
        $locationIds = $request->input('location_ids');
        // Fetch stores for these locations. Adjust logic as needed.
        $stores = StoreDetail::join(
            'business_locations',
            'store_details.location_id',
            '=',
            'business_locations.id'
        )
            ->join(
                'stores',
                'store_details.store_id',
                '=',
                'stores.id'
            )
            ->where('store_details.location_id', $locationIds)
            ->get();

        $stores = $stores->pluck('name', 'id');


        return response()->json($stores);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (! auth()->user()->can('store.create')) {
            abort(403, 'Unauthorized action.');
        }


        try {


            $input['name'] = $request['actual_name'];
            $input['code'] = $request['short_name'];
            $input['location_id'] = $request['select_location_id'];
            $input['business_id'] = $request->session()->get('user.business_id');
            $input['created_at'] =  $request->session()->get('user.id');




            $store = Store::create($input);

            $output = [
                'success' => true,
                'data' => $store,
                'msg' => __("store.added_success")
            ];
        } catch (\Exception $e) {
            dd($e->getMessage());
            \Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

            $output = [
                'success' => false,
                'msg' => __("messages.something_went_wrong")
            ];
        }

        return $output;
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
    public function edit(Request $request, $id)
    {
        if (! auth()->user()->can('store.update')) {
            abort(403, 'Unauthorized action.');
        }



        if (request()->ajax()) {
            $business_id = request()->session()->get('user.business_id');
            $store = Store::where('business_id', $business_id)->find($id);

            $locations = BusinessLocation::forDropdown($business_id);
            $menuItems = $request->menuItems;

            // dd($store);

            return view('store.edit')
                ->with(compact('store', 'locations', 'menuItems'));
        }
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
        if (! auth()->user()->can('store.update')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $input = $request->only(['actual_name', 'short_name', 'allow_decimal']);
                $business_id = $request->session()->get('user.business_id');

                $store = Store::where('business_id', $business_id)->findOrFail($id);
                $store->actual_name = $input['actual_name'];
                $store->short_name = $input['short_name'];
                $store->allow_decimal = $input['allow_decimal'];
                if ($request->has('define_base_store')) {
                    if (! empty($request->input('base_store_id')) && ! empty($request->input('base_store_multiplier'))) {
                        $base_store_multiplier = $this->commonUtil->num_uf($request->input('base_store_multiplier'));
                        if ($base_store_multiplier != 0) {
                            $store->base_store_id = $request->input('base_store_id');
                            $store->base_store_multiplier = $base_store_multiplier;
                        }
                    }
                } else {
                    $store->base_store_id = null;
                    $store->base_store_multiplier = null;
                }

                $store->save();

                $output = [
                    'success' => true,
                    'msg' => __("store.updated_success")
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (! auth()->user()->can('store.delete')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $business_id = request()->user()->business_id;

                $store = Store::where('business_id', $business_id)->findOrFail($id);

                //check if any product associated with the store
                $exists = VariationLocationDetails::where('store_id', $store->id)
                    ->exists();
                if (! $exists) {
                    $store->delete();
                    $output = [
                        'success' => true,
                        'msg' => __("store.deleted_success")
                    ];
                } else {
                    $output = [
                        'success' => false,
                        'msg' => __("lang_v1.store_cannot_be_deleted")
                    ];
                }
            } catch (\Exception $e) {

                // dd($e->getMessage());
                \Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

                $output = [
                    'success' => false,
                    'msg' => '__("messages.something_went_wrong")'
                ];
            }

            return $output;
        }
    }
}
