<?php

namespace Modules\Tracker\Actions;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Modules\Tracker\Entities\DistributionArea;

abstract class TrackAction
{
    use AsObject;

    protected function validate(Request $request){
        return $request->validate([
            'name' => 'required',
            'description' => 'required',
            'distribution_area_id' => 'required|exists:dm_distribution_areas,id',
            'user_id' => 'required|exists:users,id',
            'contact_ids' => 'nullable|array'
        ]);
    }
    protected function prepareData(array $input): array
    {
        $distributionArea = DistributionArea::find($input['distribution_area_id']);
        return [
            'name' => $input['name'],
            'description' => $input['description'],
            'user_id' => $input['user_id'],
            'distribution_area_id' => $distributionArea->id,
            'sector_id' => $distributionArea->sector_id,
            'province_id' => $distributionArea->sector->province_id,
            'business_id' => session()->get('user.business_id')
        ];
    }
}
