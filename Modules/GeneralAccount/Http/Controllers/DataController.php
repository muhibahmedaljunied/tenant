<?php

namespace Modules\GeneralAccount\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Utils\ModuleUtil;
use Menu;

class DataController extends Controller
{

    public function superadmin_package()
    {
        return [
            [
                'name' => 'GeneralAccount',
                'label' => "الحسابات العامة",
                'default' => false
            ]
        ];
    }

}
