<?php

namespace Modules\Tracker\Actions;

use Illuminate\Http\Request;
use Modules\Tracker\Entities\DistributionArea;
use Modules\Tracker\Entities\Track;

class UpdateTrackAction extends TrackAction
{
    public function handle(Request $request, Track $track)
    {
        $data = $this->prepareData($this->validate($request));
        $track->update($data);
        $track->syncContacts($request->contact_ids);
    }
}
