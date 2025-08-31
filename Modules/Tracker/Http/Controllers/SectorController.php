<?php

namespace Modules\Tracker\Http\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Tracker\Actions\CreateSectorAction;
use Modules\Tracker\Actions\UpdateSectorAction;
use Modules\Tracker\Entities\Province;
use Modules\Tracker\Entities\Sector;
use Modules\Tracker\Tables\SectorTable;
use Modules\Tracker\Tables\Table;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class SectorController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable|String
     * @throws AuthorizationException
     */
    public function index(Request $request)
    {
        $this->authorize('tracker.view_sectors');
        if (\request()->ajax()) {
            return SectorTable::render();
        }
        $menuItems = $request->menuItems;
        return view('tracker::sectors.index',compact('menuItems'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     * @throws AuthorizationException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function create(): Renderable
    {
        $this->authorize('tracker.create_sectors');
        $business_id = session()->get('user.business_id');
        $provinces = Province::select(['id', 'name'])->where('business_id', $business_id)->get()->toDropdown('id', 'name');
        $users = User::forDropdown($business_id, false);
        return view('tracker::sectors.create', compact('provinces', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return array
     * @throws Exception
     */
    public function store(Request $request): array
    {
        $this->authorize('tracker.create_sectors');
        CreateSectorAction::run($request);
        return [
            'success' => true,
            'msg' => 'Sector Created Successfully'
        ];
    }

    /**
     * Show the form for editing the specified resource.
     * @param Sector $sector
     * @return Renderable
     * @throws AuthorizationException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function edit(Sector $sector): Renderable
    {
        $this->authorize('tracker.edit_sectors');
        $business_id = session()->get('user.business_id');
        $provinces = Province::select(['id', 'name'])->where('business_id', $business_id)->get()->toDropdown('id', 'name');
        $users = User::forDropdown($business_id, false);
        return view('tracker::sectors.edit', compact('sector', 'provinces', 'users'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param Sector $sector
     * @return JsonResponse
     * @throws Exception
     */
    public function update(Request $request, Sector $sector): JsonResponse
    {
        $this->authorize('tracker.edit_sectors');
        UpdateSectorAction::run($request, $sector);
        return response()->json([
            'success' => true,
            'msg' => "Sector Updated Successfully"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @param Sector $sector
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(Sector $sector)
    {
        $this->authorize('tracker.delete_sectors');
        $sector->delete();
        return response()->json([
            'success' => true,
            'msg' => 'Sector Deleted Successfully'
        ]);
    }
}
