<?php

namespace Modules\Tracker\Http\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Tracker\Actions\CreateDistributionAreaAction;
use Modules\Tracker\Entities\DistributionArea;
use Modules\Tracker\Entities\Sector;
use Modules\Tracker\Tables\DistributionAreaTable;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class DistributionAreaController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return string
     * @throws AuthorizationException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function index(Request $request): string
    {
        $this->authorize('tracker.view_distribution_areas');
        if (request()->ajax()) {
            return DistributionAreaTable::render();
        }
        $business_id = session()->get('user.business_id');
        $sectors = Sector::with('province')->where('business_id', $business_id)->get();

        $menuItems = $request->menuItems;
        return view('tracker::distributionAreas.index', compact('sectors', 'menuItems'))->render();
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
        $this->authorize('tracker.create_distribution_areas');
        $business_id = session()->get('user.business_id');
        $sectors = Sector::where('business_id', $business_id)->get()->toDropdown('id', 'name');
        $users = User::forDropdown($business_id, false);
        return view('tracker::distributionAreas.create', compact('sectors', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function store(Request $request): JsonResponse
    {
        $this->authorize('tracker.create_distribution_areas');
        CreateDistributionAreaAction::run($request);

        return response()->json([
            'success' => true,
            'msg' => 'Distribution Area Created Successfully'
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     * @param DistributionArea $distributionArea
     * @return Renderable
     * @throws AuthorizationException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function edit(DistributionArea $distributionArea): Renderable
    {
        $this->authorize('tracker.edit_distribution_areas');
        $business_id = session()->get('user.business_id');
        $sectors = Sector::where('business_id', $business_id)->get()->toDropdown('id', 'name');
        $users = User::forDropdown($business_id, false);
        return view('tracker::distributionAreas.edit', compact('distributionArea', 'sectors', 'users'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param DistributionArea $distributionArea
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function update(Request $request, DistributionArea $distributionArea): JsonResponse
    {
        $this->authorize('tracker.edit_distribution_areas');
        $data = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'phone' => 'required',
            'sector_id' => 'required|exists:dm_sectors,id',
            'user_id' => 'required|exists:users,id',
            'points' => 'required|json'
        ]);

        $distributionArea->update($data);

        return response()->json([
            'success' => true,
            'msg' => 'Distribution Area Updated Successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @param DistributionArea $distributionArea
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(DistributionArea $distributionArea): JsonResponse
    {
        $this->authorize('tracker.delete_distribution_areas');
        $distributionArea->delete();
        return response()->json([
            'success' => true,
            'msg' => "Distribution Area Deleted Successfully"
        ]);
    }
}
