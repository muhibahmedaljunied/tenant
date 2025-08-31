<?php

namespace Modules\Tracker\Http\Controllers;

use App\Contact;
use App\Http\Controllers\Controller;
use App\User;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Tracker\Actions\CreateTrackAction;
use Modules\Tracker\Actions\UpdateTrackAction;
use Modules\Tracker\Entities\DistributionArea;
use Modules\Tracker\Entities\Province;
use Modules\Tracker\Entities\Sector;
use Modules\Tracker\Entities\Track;
use Modules\Tracker\Tables\TrackTable;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class TrackController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable|string
     * @throws AuthorizationException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function index(Request $request)
    {
        $this->authorize('tracker.view_tracks');
        if (request()->ajax()) {
            return TrackTable::render();
        }
        $business_id = session()->get('user.business_id');
        $sectors = Sector::where('business_id', $business_id)->get();
        $provinces = Province::where('business_id', $business_id)->get();
        $distributionAreas = DistributionArea::where('business_id', $business_id)->get();
        $users = User::where('business_id', $business_id)->get();
        $contacts = Contact::onlyCustomers()->where('business_id', $business_id)->whereNotNull('track_id')->get();
        $menuItems = $request->menuItems;
        return view('tracker::tracks.index', compact(
            'sectors',
            'provinces',
            'distributionAreas',
            'users',
            'contacts',
            'menuItems'
        ));
    }


    public function show(Track $track)
    {
        return view('tracker::tracks.show', compact('track'));
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
        $this->authorize('tracker.create_tracks');
        $business_id = session()->get('user.business_id');
        $contacts = Contact::where('business_id', $business_id)->whereNull('track_id')->onlyCustomers()->get()->toDropdown('id', 'name');
        $distributionAreas = DistributionArea::where('business_id', $business_id)->get()->toDropdown('id', 'name');
        $users = User::forDropdown($business_id, false);
        return view('tracker::tracks.create', compact('distributionAreas', 'users', 'contacts'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function store(Request $request): JsonResponse
    {
        $this->authorize('tracker.create_tracks');
        CreateTrackAction::run($request);

        return response()->json([
            'success' => true,
            'msg' => __('tracker::lang.track_created')
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     * @param Track $track
     * @return Renderable
     * @throws AuthorizationException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function edit(Track $track): Renderable
    {
        $this->authorize('tracker.edit_tracks');
        $business_id = session()->get('user.business_id');
        $distributionAreas = DistributionArea::where('business_id', $business_id)->get()->toDropdown('id', 'name');
        $users = User::forDropdown($business_id, false);
        $contacts = Contact::where('business_id', $business_id)->where(function ($query) use ($track) {
            $query->where('track_id', $track->id)->orWhereNull('track_id');
        })->onlyCustomers()->get()->toDropdown('id', 'name');
        $trackContacts = $track->contacts()->pluck('contacts.id');
        return view('tracker::tracks.edit', compact('track', 'trackContacts', 'distributionAreas', 'users', 'contacts'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param Track $track
     * @return JsonResponse
     * @throws Exception
     */
    public function update(Request $request, Track $track): JsonResponse
    {
        $this->authorize('tracker.edit_tracks');
        UpdateTrackAction::run($request, $track);

        return response()->json([
            'success' => true,
            'msg' => __('tracker::lange.track_updated')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @param Track $track
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(Track $track): JsonResponse
    {
        $this->authorize('tracker.delete_tracks');
        if ($track->contacts()->exists() && !request()->filled('force')) {
            return response()->json([
                'success' => false,
                'msg' => __('tracker::lang.track_has_contacts')
            ], 422);
        }
        $track->delete();

        return response()->json([
            'success' => true,
            'msg' => __('tracker::lang.track_deleted')
        ]);
    }
}
