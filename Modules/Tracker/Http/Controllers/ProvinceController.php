<?php

namespace Modules\Tracker\Http\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Tracker\Actions\CreateProvinceAction;
use Modules\Tracker\Entities\Province;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ProvinceController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     * @throws AuthorizationException
     */
    public function index(Request $request)
    {
        $this->authorize('tracker.view_provinces');
        if (\request()->ajax()) {
            $business_id = auth()->user()->business_id;
            $output = '';
            $data = Province::where('business_id', $business_id)->get();


            $total = 0;
            foreach ($data as $row) {
                /** @var User $user */
                $user = User::find($row->user_id);
                $userName = $user ? $user->getUserFullNameAttribute() : "";
                $total = $total + $row->value;
                $output .= '<tr id="' . $row->id . '">';
                $output .= '<td>' . $row->name . '</td>';
                $output .= '<td>' . $row->description . '</td>';
                $output .= "<td>{$userName}</td>";
                $output .= '<td> ';
                if (auth()->user()->can('tracker.edit_provinces')) {
                    $output .= '<button onclick="editProvince(' . $row->id . ')"  class="btn btn-xs btn-primary btn-modal"><i class="glyphicon glyphicon-edit"></i> تعديل</button>';
                }
                if(auth()->user()->can('tracker.delete_provinces')) {
                    $output .= '<button onclick="deleteProvince(' . $row->id . ')" class="btn btn-xs btn-danger "><i class="glyphicon glyphicon-trash"></i> حذف</button>';
                }
                $output .= '</td></tr>';
            }

            return $output;
        }

        $menuItems = $request->menuItems;
        return view('tracker::provinces.index',compact('menuItems'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     * @throws AuthorizationException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function create()
    {
        abort_if(!request()->ajax(), 404);
        $this->authorize('tracker.create_provinces');
        $business_id = session()->get('user.business_id');
        $users = User::forDropdown($business_id, false);
        return view('tracker::provinces.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return array
     * @throws AuthorizationException
     */
    public function store(Request $request)
    {
        $this->authorize('tracker.create_provinces');
        CreateProvinceAction::run($request);
        return [
            'success' => true,
            'msg' => 'Province Created Successfully'
        ];
    }


    /**
     */
    public function edit(Province $province)
    {
        $this->authorize('tracker.edit_provinces');
        $business_id = session()->get('user.business_id');
        $users = User::forDropdown($business_id);
        return view('tracker::provinces.edit', compact('province', 'users'));
    }

    /**
     * @throws AuthorizationException
     */
    public function update(Province $province, Request $request)
    {
        $this->authorize('tracker.edit_provinces');
        $data = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'phone' => 'required',
            'user_id' => 'required|exists:users,id'
        ]);

        $province->update($data);

        return response()->json([
            'success' => true,
            'msg' => "Province Updated Successfully"
        ]);
    }

    /**
     * @throws AuthorizationException
     */
    public function destroy(Province $province){
        $this->authorize('tracker.delete_provinces');
        $province->delete();

        return [
            'success' => true,
            'msg' => 'Province deleted successfully'
        ];
    }
}
