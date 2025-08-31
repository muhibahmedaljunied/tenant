<?php

namespace Modules\Tracker\Actions;

use Illuminate\Http\Request;
use Modules\Tracker\Entities\Sector;

class UpdateSectorAction
{
    use AsObject;

    private function validate(Request $request){
        return $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'description' => 'required',
            'province_id' => 'required|exists:dm_provinces,id',
            'user_id' => 'required|exists:users,id'
        ]);
    }

    public function handle(Request $request, Sector $sector): bool
    {
        $data = $this->validate($request);
        return $sector->update($data);
    }
}
