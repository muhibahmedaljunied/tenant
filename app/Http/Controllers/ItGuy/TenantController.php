<?php

namespace App\Http\Controllers\ItGuy;

use Illuminate\Http\Request;
use App\Helpers\Qs;
use App\Helpers\Usr;
use App\Helpers\Pay;
use App\Http\Controllers\Controller;
use App\Http\Requests\TenantRequest;
use App\Models\Tenant as ModelsTenant;
use App\Repositories\TenantRepo;
use App\Repositories\UserRepo;
use Illuminate\Support\Facades\Log;
use Stancl\Tenancy\Database\Models\Tenant;
use Yajra\DataTables\Facades\DataTables;

use function Illuminate\Log\log;

class TenantController extends Controller
{
    protected $tenant, $user;
    public function __construct(
        TenantRepo $tenant,
        UserRepo $user
    ) {

        $this->tenant = $tenant;
        $this->user = $user;
    }



    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $menuItems = $request['menuItems'];
        $tenant = $this->tenant->getAll();
        if (request()->ajax()) {

            $tenant = $this->tenant->getAll();


            // dd($tenant);

            return DataTables::of($tenant)

                ->addColumn('name', fn($tenant) => $tenant->name)
                ->addColumn('domain', fn($tenant) => $tenant->domain->domain ?? '')
                ->addColumn('account_status', fn($tenant) => $tenant->account_status)
                ->addColumn('payment_status', fn($tenant) => $tenant->payment_status)
                ->addColumn('created_at', fn($tenant) => $tenant->created_at->format('Y-m-d H:i:s'))
                ->addColumn('updated_at', fn($tenant) => $tenant->updated_at->format('Y-m-d H:i:s'))
                // ->addColumn(
                //     'action',
                //     '
                //     <button data-href="{{action(\'TenantController@edit\', [$id])}}" class="btn btn-xs btn-primary edit_store_button"><i class="glyphicon glyphicon-edit"></i> @lang("messages.edit")</button>
                //         &nbsp;
                //         <button data-href="{{action(\'TenantController@destroy\', [$id])}}" class="btn btn-xs btn-danger delete_store_button"><i class="glyphicon glyphicon-trash"></i> @lang("messages.delete")</button>
                //    '
                // )
                // ->addColumn('action', function ($tenant) {
                //     $html = '<button data-href="' . action('\ItGuy\TenantController@edit', [$tenant->id]) . '" class="btn btn-xs btn-primary edit_store_button">
                //                 <i class="fa fa-eye" aria-hidden="true"></i> ' . __("messages.view") . '
                //             </button>';

                //     $html .= '&nbsp;<button data-href="' . action('\ItGuy\TenantController@destroy', [$tenant->id]) . '" class="btn btn-xs btn-primary edit_store_button">
                //                 <i class="fa fa-money" aria-hidden="true"></i> ' . __("purchase.view_payments") . '
                //             </button>';

                //     return $html;
                // })
                ->addColumn('action', function ($tenant) {
                    $editUrl = route('tenants.edit', $tenant->id);
                    $deleteUrl = route('tenants.destroy', $tenant->id);

                    return '
                        <button data-href="' . $editUrl . '" class="btn btn-xs btn-primary edit_tenant_button">
                            <i class="glyphicon glyphicon-edit"></i> ' . __('messages.edit') . '
                        </button>
                               &nbsp;
                                 <button data-href="' . $editUrl . '" class="btn btn-xs btn-info view_tenant_button">
                            <i class="fa fa-eye"></i>> ' . __('messages.view') . '
                        </button>
                        &nbsp;
                        <button data-href="' . $deleteUrl . '" class="btn btn-xs btn-danger delete_tenant_button">
                            <i class="glyphicon glyphicon-trash"></i> ' . __('messages.delete') . '
                        </button>
                    ';
                })



                ->rawColumns(['action'])
                ->make(true);
        }

        return view("pages.it_guy.tenants.index",  compact(

            'menuItems',

        ));
    }

    /**
     * Get the middleware that should be assigned to the controller.
     */
    // public static function middleware(): array
    // {
    //     return [
    //         new middleware('headSA', only: ['destroy']),
    //     ];
    // }

    public function show($id)
    {
        $id = Qs::decodeHash($id);
        $data["tenant"] = $this->tenant->find($id);

        return view("pages.it_guy.tenants.show", $data);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // if (!auth()->user()->can('customer.create')) {
        //     abort(403, 'Unauthorized action.');
        // }


        $menuItems = $request['menuItems'];
        $account_status = Usr::getAccountStatuses();
        $payment_status = Pay::getPaymentStatuses();
        return view("pages.it_guy.tenants.create", compact(
            'menuItems',
            'account_status',
            'payment_status'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        // TenantRequest $request,
        Request $requests
    ) {





        // dd($requests->all());
        set_time_limit(600); // Set time limit for this method to 10 minutes.
        $data = $requests->except(['_token', '_method', 'domain', 'menuItems']);
        Log::info('This is an info message');

        // $data['tenancy_db_name'] =   'tenant_123';
        // $data['name'] =   $data['name'];

        // Log::info('Listener triggered for SomeEvent');
        // dd($data);
        try {

            // $tenant = new Tenant();
            // $tenant->data = $data; // This assumes 'data' is a JSON column in your database
            // $tenant->save();


            $tenant = $this->tenant->create($data);
        } catch (\Exception $e) {
            // When creating tenant, database, and seeding the database etc., operations will proceed as usual.
            // If there was any exception ie., error. Delete the created ones and return the error.
            // The session's key 'created_tenant_id' is defined in TenancyServiceProvider.
            // Can't access the '$tenant' variable in try block above, since it is in different scope.


            // log::error('fffffffffffffffffffffffdddddddd');
            Log::debug($e->getMessage());
            if (session()->has("created_tenant_id")) {
                $this->tenant->delete(session()->get("created_tenant_id"));
            }
            session()->forget("created_tenant_id");
            return Qs::json($e->getMessage(), false);
        }


        $tenant->createDomain(['domain' => $requests->domain]);


        session()->forget("created_tenant_id");



        return back()->with('pop_success', __('msg.store_ok'))->with('pop_timer', 0);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {

        if (request()->ajax()) {

            $tenant = ModelsTenant::with('domain')->where('id', $id)->first();
            $account_status = Usr::getAccountStatuses();
            $payment_status = Pay::getPaymentStatuses();
            $menuItems = $request->menuItems;

            // dd($tenant);
            // dd($tenant,$account_status,$payment_status);

            return view('pages.it_guy.tenants.edit')
                ->with(compact(
                    'tenant',
                    'account_status',
                    'payment_status',
                    'menuItems'
                ));
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


        if (request()->ajax()) {
            // try {
            //     $input = $request->only(['actual_name', 'short_name', 'allow_decimal']);
            //     $business_id = $request->session()->get('user.business_id');

            //     $store = Store::where('business_id', $business_id)->findOrFail($id);
            //     $store->actual_name = $input['actual_name'];
            //     $store->short_name = $input['short_name'];
            //     $store->allow_decimal = $input['allow_decimal'];
            //     if ($request->has('define_base_store')) {
            //         if (! empty($request->input('base_store_id')) && ! empty($request->input('base_store_multiplier'))) {
            //             $base_store_multiplier = $this->commonUtil->num_uf($request->input('base_store_multiplier'));
            //             if ($base_store_multiplier != 0) {
            //                 $store->base_store_id = $request->input('base_store_id');
            //                 $store->base_store_multiplier = $base_store_multiplier;
            //             }
            //         }
            //     } else {
            //         $store->base_store_id = null;
            //         $store->base_store_multiplier = null;
            //     }

            //     $store->save();

            //     $output = [
            //         'success' => true,
            //         'msg' => __("store.updated_success")
            //     ];
            // } catch (\Exception $e) {
            //     \Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

            //     $output = [
            //         'success' => false,
            //         'msg' => __("messages.something_went_wrong")
            //     ];
            // }

            // return $output;
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


        if (request()->ajax()) {
            // try {
            //     $business_id = request()->user()->business_id;

            //     $store = Store::where('business_id', $business_id)->findOrFail($id);

            //     //check if any product associated with the store
            //     $exists = VariationLocationDetails::where('store_id', $store->id)
            //         ->exists();
            //     if (! $exists) {
            //         $store->delete();
            //         $output = [
            //             'success' => true,
            //             'msg' => __("store.deleted_success")
            //         ];
            //     } else {
            //         $output = [
            //             'success' => false,
            //             'msg' => __("lang_v1.store_cannot_be_deleted")
            //         ];
            //     }
            // } catch (\Exception $e) {

            //     // dd($e->getMessage());
            //     \Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

            //     $output = [
            //         'success' => false,
            //         'msg' => '__("messages.something_went_wrong")'
            //     ];
            // }

            // return $output;
        }
    }
}
