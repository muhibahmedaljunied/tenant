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
                ->addColumn(
                    'action',
                    '
                        <a href="#" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> @lang("messages.edit")</a>
                  
                    
                    <a href="{#" class="btn btn-xs btn-info"><i class="fa fa-eye"></i> @lang("messages.view")</a>
                   
                 
             
                        <button data-href="#" class="btn btn-xs btn-danger delete_user_button"><i class="glyphicon glyphicon-trash"></i> @lang("messages.delete")</button>
                   '
                )
             
                ->removeColumn('id')
                ->rawColumns(['action'])
                ->make(true);
        }





        $menuItems = $request->menuItems;

        return view('pages.it_guy.users.index', compact(

            'menuItems'
        ));
    }







    public function edit($id)
    {
        $id = Qs::decodeHash($id);
        $ut = $this->user->getAllNotStudentType();
        $ut2 = $ut->where('level', '>', 2);

        $d['user_types'] = Qs::userIsAdmin() ? $ut2 : $ut;
        $d['user'] = $this->user->find($id);
        $d['staff_rec'] = $this->user->getStaffRecord(['user_id' => $id])->first();
        $d['users'] = $this->user->getPTAUsers();
        $d['blood_groups'] = $this->user->getBloodGroups();
        $d['nationals'] = $this->loc->getAllNationals();

        return view('pages.it_guy.users.edit', $d);
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

    public function update(UserRequest $req, $id)
    {
        $id = (int) Qs::decodeHash($id);
        $user_type_id = (int) Qs::decodeHash($req->user_type_id);
        $user = $this->user->find($id);
        $user_type = $this->user->findType($user_type_id)->title ?? $user->user_type;
        $except = array_merge(Qs::getStaffRecord(), ['_token', '_method', 'user_type_id']);
        $data = $req->except($except);

        $data['name'] = $name = ucwords(strtolower($req->name));
        $data['user_type'] = $user_type;
        $data['message_media_heading_color'] = '#' . substr(md5($name), 0, 6); // Use a unique text color based on the name

        if ($req->hasFile('photo')) {
            $photo = $req->file('photo');
            $f = Qs::getFileMetaData($photo);
            $f['name'] = 'photo.' . $f['ext'];
            $f['path'] = $photo->storeAs(Qs::getUploadPath($user_type) . $user->code, $f['name']);
            $data['photo'] = 'storage/' . $f['path'];
        }

        $this->user->update($id, $data);   /* Update user record */

        /* Update staff record */
        $d2 = $req->only(Qs::getStaffRecord());
        $d2['code'] = $user->code;
        $d2['subjects_studied'] = isset($d2['subjects_studied']) ? json_encode(explode(",", $d2['subjects_studied'])) : NULL;
        $this->user->updateStaffRecord(['user_id' => $id], $d2);

        return Qs::jsonUpdateOk();
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
