<?php

namespace Modules\Tracker\Actions;

use Illuminate\Http\Request;
use Modules\Tracker\Entities\Province;
use Modules\Tracker\Entities\Sector;

class CreateSectorAction
{
    use AsObject;
    private function validate(Request $request){
        return $request->validate([
            'name' => 'required',
            'description' => 'required',
            'phone' => 'required',
            'province_id' => 'required|exists:dm_provinces,id',
            'user_id' => 'required|exists:users,id'
        ]);
    }

    public function handle(Request $request){
        $data = $this->validate($request);
        $data['business_id'] = $request->session()->get('user.business_id');
        return Sector::create($data);
    }
}
