<?php

namespace App\Http\Controllers;

use App\Utils\ModuleUtil;
use App\Models\AcAsset;
use App\Models\AcAssetClass;
use App\Models\AcMaster;
use App\Product;
use App\Unit;
use App\Utils\ProductUtil;
use App\Utils\Util;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AcAssetController extends Controller
{
    protected $commonUtil;
    protected $productUtil;
    protected $moduleUtil;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(
        Util $commonUtil,
        ProductUtil $productUtil,
        ModuleUtil $moduleUtil
    ) {
        $this->commonUtil = $commonUtil;
        $this->productUtil = $productUtil;
        $this->moduleUtil = $moduleUtil;
    }
    /**
     * Display a listing of the resource.
     *
     * @return 
     */
    public function index(Request $request)
    {

        if (request()->ajax()) {
            $business_id = request()->session()->get('user.business_id');
            if ($request->has('notable')) {
                $query = AcAsset::where('business_id', $business_id)->with('ac_asset_class');
                if ($request->filled('class_id')) {
                    $query->where('asset_classes_id', $request->input('class_id'));
                }
                $assets = $query->get();
                if ($request->has('has_transaction')) {
                    return $assets->filter(function ($asset) {
                        return $asset->hasTransaction() && $asset->transaction()->exists();
                    })->map(function ($asset) {
                        $asset->transaction = $asset->transaction();
                        return $asset;
                    })->values();
                }
                return $assets;
            }

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
            $rawColumns = [
                'action',
                'asset_class_name',
                'asset_account',
                'is_depreciable',
                'asset_expense_account',
                'accumulated_consumption_account',
                'useful_life_type',
                'row_no'
            ];

            return $datatable->rawColumns($rawColumns)
                ->make(true);
        }

        $menuItems = $request->menuItems;
        return view('ac_asset.index', compact('menuItems'));
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
        $asset_classes = AcAssetClass::where('business_id', $business_id)->pluck('asset_class_name_ar', 'id')->toArray();
        $menuItems = $request->menuItems;
        return view('ac_asset.create')
            ->with(compact(
                'lastChildrenBranch',
                'asset_classes',
                'menuItems'
            ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $input = $request->except('_token', 'submit_type');
        // dd($request->all());
        // try {
        $asset['asset_name_ar'] = $input['asset_name_ar'];
        $asset['asset_name_en'] = $input['asset_name_en'];
        $asset['asset_classes_id'] = $input['asset_classes_id'];
        $asset['asset_description'] = $input['asset_description'];
        if (isset($input['asset_value'])) {
            $asset['asset_value'] = $input['asset_value'];
        }
        if (isset($input['scrap_value'])) {
            $asset['scrap_value'] = $input['scrap_value'];
        }
        $asset_record = AcAsset::create($asset);

        $business_id = request()->session()->get('user.business_id');
        $asset_class_details = AcAssetClass::where('business_id', $business_id)->find($asset['asset_classes_id']);

        // dd($asset_class_details);
        $asset_account = $asset_class_details->asset_account;
        $max_account_children_asset  = AcMaster::where('parent_acct_no', $asset_account)->orderBy('account_number', 'desc')->value('account_number');
        $next_children_asset = $asset_account . '01';
        if (!empty($max_account_children_asset)) {
            $next_children_asset = $max_account_children_asset + 1;
        }
        $level_asset  = $asset_class_details->asset_account_details->account_level + 1;
        $asset_account_account['account_name_ar'] = $input['asset_name_ar'];
        $asset_account_account['account_name_en'] = $input['asset_name_en'];
        $asset_account_account['account_number'] = $next_children_asset;
        $asset_account_account['account_level'] = $level_asset;
        $asset_account_account['parent_acct_no'] = $asset_account;
        $asset_account_account['account_type'] = $asset_class_details->asset_account_details->account_type;
        $ac_master_asset_account = AcMaster::create($asset_account_account);
        if ($ac_master_asset_account) {
            $asset_account_input['asset_account'] = $asset_account_account['account_number'];
            $asset_record->update($asset_account_input);
        }

        if ($asset_class_details->is_depreciable) {


            $asset_expense_account = $asset_class_details->asset_expense_account;
            $max_account_children_expense  = AcMaster::where('parent_acct_no', $asset_expense_account)->orderBy('account_number', 'desc')->value('account_number');
            $next_children_expense = $asset_expense_account . '01';
            if (!empty($max_account_children_expense)) {
                $next_children_expense = $max_account_children_expense + 1;
            }
            $level_expense = $asset_class_details->asset_expense_account_details->account_level + 1;
            $expense_account['account_name_ar'] = "مصروف  اهلاك " . $input['asset_name_ar'];
            $expense_account['account_name_en'] = $input['asset_name_en'];
            $expense_account['account_number'] = $next_children_expense;
            $expense_account['account_level'] = $level_expense;
            $expense_account['parent_acct_no'] = $asset_expense_account;
            $expense_account['account_type'] = $asset_class_details->asset_expense_account_details->account_type;
            $ac_master_expense_account = AcMaster::create($expense_account);
            if ($ac_master_expense_account) {
                $asset_account_input['asset_expense_account'] = $expense_account['account_number'];
            }


            $accumulated_consumption_account = $asset_class_details->accumulated_consumption_account;
            $max_account_children_accumulated  = AcMaster::where('parent_acct_no', $accumulated_consumption_account)->orderBy('account_number', 'desc')->value('account_number');
            $next_children_accumulated = $accumulated_consumption_account . '01';
            if (!empty($max_account_children_accumulated)) {
                $next_children_accumulated = $max_account_children_accumulated + 1;
            }
            $level_accumulated = $asset_class_details->accumulated_consumption_account_details->account_level + 1;
            $accumulated_account['account_name_ar'] = "مجمع  اهلاك " . $input['asset_name_ar'];
            $accumulated_account['account_name_en'] = $input['asset_name_en'];
            $accumulated_account['account_number'] = $next_children_accumulated;
            $accumulated_account['account_level'] = $level_accumulated;
            $accumulated_account['parent_acct_no'] = $accumulated_consumption_account;
            $accumulated_account['account_type'] = $asset_class_details->accumulated_consumption_account_details->account_type;
            $ac_master_accumulated_account = AcMaster::create($accumulated_account);
            if ($ac_master_accumulated_account) {
                $asset_account_input['accumulated_consumption_account'] = $accumulated_account['account_number'];
            }
            $unit = Unit::where('business_id', $business_id)->first();
            $product = Product::create([
                'name' => $input['asset_name_ar'],
                'barcode' => $input['barcode'],
                'barcode_type' => 'C128',
                'enable_stock' => true,
                'is_asset' => true,
                'business_id' => $business_id,
                'unit_id' => $unit->id,
            ]);
            $product->product_locations()->sync($business_id);
            $product->sku = $this->productUtil->generateProductSku($product->id);
            $this->productUtil->createSingleProductVariation($product->id, $product->sku, $input['asset_value'], $input['asset_value'], 0, 0, 0, $product->sku2);
            if (!empty($request->input('has_module_data'))) {
                $this->moduleUtil->getModuleData('after_product_saved', ['product' => $product, 'request' => $request]);
            }
            $asset_record->update([
                'product_id' => $product->id
            ]);
        }

        $asset_record->update($asset_account_input);

        $output = [
            'success' => true,
            'data' => $input,
            'msg' => __("lang_v1.success")
        ];
        // } catch (\Exception $e) {
        //     \Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

        //     $output = [
        //         'success' => false,
        //         'msg' => __("messages.something_went_wrong")
        //     ];
        // }
        return redirect()
            ->action('AcAssetController@index')
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
        return view('ac_asset.edite')
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
            } else {
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

    public function getAssetClassesDetails(Request $request)
    {
        $output = [];

        if (request()->ajax()) {
            $business_id = request()->session()->get('user.business_id');
            $asset_classes_id = $request->asset_classes_id;
            $asset_class_record = AcAssetClass::findOrFail($asset_classes_id);
            $lastChildrenBranch = AcMaster::whereDoesntHave('parents')->selectRaw("account_number, concat(account_name_ar, ' (', account_number , ')') as account_name_number")->pluck('account_name_number', 'account_number')->toArray();
            $output['success'] = true;
            $output['msg'] = __('lang_v1.done');
            $output['html_content'] =  view('ac_asset.includes.asset_classes_details')
                ->with(compact('asset_class_record', 'lastChildrenBranch'))->render();
        } else {
            $output['success'] = false;
            $output['msg'] = __('lang_v1.xxx');
        }


        return $output;
    }
}
