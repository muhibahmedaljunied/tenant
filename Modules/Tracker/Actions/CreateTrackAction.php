<?php

namespace Modules\Tracker\Actions;

use App\Contact;
use Illuminate\Http\Request;
use Modules\Tracker\Entities\DistributionArea;
use Modules\Tracker\Entities\Track;

class CreateTrackAction extends TrackAction
{
    public function handle(Request $request){
        $data = $this->prepareData($this->validate($request));

        $track = Track::create($data);
        $contacts = Contact::whereIn('id', $request->contact_ids)->get();
        $track->contacts()->saveMany($contacts);
        return $track;
    }
}
