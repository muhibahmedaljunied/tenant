<?php

namespace App\Http\Controllers\ItGuy;

use Illuminate\Http\Request;
use App\Helpers\Qs;
use App\Helpers\Usr;
use App\Http\Controllers\Controller;
// use App\Models\User;
use App\User;
use App\Repositories\UserRepo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    protected $user, $loc, $my_class;

    public function __construct(
        UserRepo $user,


    ) {

        // $this->moduleUtil = $moduleUtil;

        $this->user = $user;
    }




    public function index(Request $request)
    {


        // if (!auth()->user()->can('user.view') && !auth()->user()->can('user.create')) {
        //     abort(403, 'Unauthorized action.');
        // }


        if (request()->ajax()) {



            $users = User::select();

            // dd($users);
            return Datatables::of($users)
                ->editColumn('username', '{{$username}}')
                ->addColumn(
                    'name',
                    '{{$first_name}}'
                )
                ->addColumn(
                    'role',
                    3
                )
                ->addColumn(
                    'email',
                    '{{$email}}'
                )

                ->addColumn('action', function ($users) {
                    $editUrl = route('users.edit', $users->id);
                    $deleteUrl = route('users.destroy', $users->id);
                    // $vieweUrl = route('users.view', $users->id);

                    return '
                        <button data-href="' . $editUrl . '" class="btn btn-xs btn-primary edit_user_button">
                            <i class="glyphicon glyphicon-edit"></i> ' . __('messages.edit') . '
                        </button>
                        &nbsp;
                                 <button data-href="' . $editUrl . '" class="btn btn-xs btn-info view_user_button">
                            <i class="fa fa-eye"></i>> ' . __('messages.delete') . '
                        </button>
                                &nbsp;
                        <button data-href="' . $deleteUrl . '" class="btn btn-xs btn-danger delete_user_button">
                            <i class="glyphicon glyphicon-trash"></i> ' . __('messages.view') . '
                        </button>
                    ';
                })

                // ->addColumn(
                //     'action',
                //     '
                //         <a href="#" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> @lang("messages.edit")</a>
                  
                    
                //     <a href="{#" class="btn btn-xs btn-info"><i class="fa fa-eye"></i> @lang("messages.view")</a>
                   
                 
             
                //         <button data-href="#" class="btn btn-xs btn-danger delete_user_button"><i class="glyphicon glyphicon-trash"></i> @lang("messages.delete")</button>
                //    '
                // )

                ->removeColumn('id')
                ->rawColumns(['action'])
                ->make(true);
        }





        $menuItems = $request->menuItems;

        return view('pages.it_guy.users.index', compact(

            'menuItems'
        ));
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
            $business_id = request()->session()->get('user.business_id');
            $user = User::find($id);

            // $locations = BusinessLocation::forDropdown($business_id);
            $menuItems = $request->menuItems;

            // dd($store);

            return view('pages.it_guy.users.edit')
                ->with(compact(
                    'user',
                    // 'locations',
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

    public function reset_pass($id)
    {
        $data['password'] = Hash::make('user');
        $data['password_updated_at'] = NULL;
        $this->user->update($id, $data);

        return back()->with('flash_success', __('msg.pu_reset'));
    }

    public function store(UserRequest $req)
    {
        $user_type = $this->user->findType($req->user_type)->title;
        $except = ['_token', '_method'];
        $data = $req->except(array_merge(Qs::getStaffRecord(), Qs::getParentRelativeRecord(), $except));

        $data['name'] = $name = ucwords(strtolower($req->name));
        $data['user_type'] = $user_type;
        $data['code'] = $code = strtoupper(Str::random(10));
        $data['photo'] = "storage/" . Usr::createAvatar($name, $code, $user_type);
        $data['dob'] = $req->dob;

        $emp_date = $req->emp_date ?? now();
        $staff_id = Qs::getAppCode() . '/STAFF/' . date('Y/m', strtotime($emp_date)) . '/' . mt_rand(1000, 9999);
        $data['username'] = $uname = $req->username ?? $staff_id;

        $pass = $req->password ?: $user_type;
        $data['password'] = Hash::make($pass);
        $data['message_media_heading_color'] = '#' . substr(md5($name), 0, 6); // Use a unique text color based on the name

        if ($req->hasFile('photo')) {
            $photo = $req->file('photo');
            $f = Qs::getFileMetaData($photo);
            $f['name'] = 'photo.' . $f['ext'];
            $f['path'] = $photo->storeAs(Qs::getUploadPath($user_type) . $data['code'], $f['name']);
            $data['photo'] = 'storage/' . $f['path'];
        }

        /* Ensure that both username and Email are not blank*/
        if (!$uname && !$req->email)
            return Qs::json(__('msg.user_invalid'), FALSE);

        $user = $this->user->create($data); // Create User

        /* Create staff record */
        $d2 = $req->only(Qs::getStaffRecord());
        $d2['user_id'] = $user->id;
        $d2['code'] = $staff_id;
        $d2['subjects_studied'] = isset($d2['subjects_studied']) ? json_encode(explode(",", $d2['subjects_studied'])) : NULL;

        $this->user->createStaffRecord($d2);

        return Qs::jsonStoreOk();
    }



    public function create(Request $request)
    {

        $menuItems = $request['menuItems'];
        $account_status = Usr::getAccountStatuses();
        $payment_status = Usr::getAccountStatuses();

        return view("pages.it_guy.users.create", compact(
            'menuItems',
            'account_status',
            'payment_status'

        ));
    }


    public function show($user_id)
    {
        $user_id = Qs::decodeHash($user_id);
        if (!$user_id)
            return back();

        $data['user'] = $this->user->find($user_id);

        /* Prevent other Users from viewing Profile of others */
        if (auth()->id() != $user_id && !Qs::userIsHead())
            return back()->with('pop_error', __('msg.denied'));

        $data['staff_rec'] = $this->user->getStaffRecord(['user_id' => $user_id])->first() ?: null;

        return view('pages.it_guy.users.show', $data);
    }

    public function destroy($id)
    {
        $id = Qs::decodeHash($id);
        $user = $this->user->find($id);
        $path = Qs::getUploadPath($user->user_type) . $user->code;

        Storage::exists($path) ? Storage::deleteDirectory($path) : true;

        $this->user->forceDelete($user->id);

        return back()->with('flash_success', __('msg.del_ok'));
    }

    public function update_user_blocked_state(UserBlockedState $req)
    {
        $user_id = $req->id;
        $data = $req->only("blocked");
        $this->user->update($user_id, $data);

        return Qs::json("ok");
    }
}
