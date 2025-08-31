<?php

namespace Modules\Tracker\Actions;

use Illuminate\Http\Request;
use Modules\Tracker\Entities\DistributionArea;
use Modules\Tracker\Entities\Province;
use Modules\Tracker\Entities\Sector;

class CreateDistributionAreaAction
{
    use AsObject;
    private function validate(Request $request){
        return $request->validate([
            'name' => 'required',
            'description' => 'required',
            'phone' => 'required',
            'sector_id' => 'required|exists:dm_sectors,id',
            'user_id' => 'required|exists:users,id',
            'points' => 'nullable|json'
        ]);
    }

    public function handle(Request $request){
        $data = $this->validate($request);
        $data['business_id'] = $request->session()->get('user.business_id');
        if(!$request->filled('points')){
            $data['points'] = "[]";
        }
        return DistributionArea::create($data);
    }
}
