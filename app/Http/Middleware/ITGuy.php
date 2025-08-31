<?php

namespace App\Http\Middleware;

use Closure;
// use App\Models\Zatca;
// use App\Utils\ModuleUtil;
// use Illuminate\Support\Facades\Auth;
// use App\Models\User;


class ITGuy
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {


        if ($request->ajax()) {
            return $next($request);
        }


        $menuItems = [];
        // dd(auth()->user()->hasRole('Admin#' . session('business.id')));

        $menuItems[__('home.home')] = [
            [

                'name' => __('home.home'),
                'url' => action('ItGuy\HomeController@index'),
                'icon' => 'fa fas fa-tachometer-alt',
                'active' => request()->segment(1) == 'home',
                'order' => 5,
            ]
        ];



        // dd(auth()->user()->can('chartofaccounts.view'));
        // $user = Auth::user();
        // $user = User::find($user->id);
        // $is_admin = auth()->user()->hasRole('Admin#' . session('business.id'));
        // $enabled_modules = !empty(session('business.enabled_modules')) ? session('business.enabled_modules') : [];

        // if (auth()->user()->can('user.view') || auth()->user()->can('user.create') || auth()->user()->can('roles.view')) {

        $menuItems[__('tenant.tenant_management')] = [
            [
                'name' => __('tenant.tenant_management'),
                'icon' => 'fa fas fa-users',
                'order' => 10,
                'sub' => [
                    [
                        'name' => __('tenant.tenants'),
                        'url' => action('ItGuy\TenantController@index'),
                        'icon' => 'fa fas fa-user',
                        'active' => request()->segment(1) == 'tenants',
                    ],
               
                ],
            ]
        ];
            $menuItems[__('user.user_management')] = [
                [
                    'name' => __('user.user_management'),
                    'icon' => 'fa fas fa-users',
                    'order' => 10,
                    'sub' => [
                        [
                            'name' => __('user.users'),
                            'url' => action('ItGuy\UserController@index'),
                            'icon' => 'fa fas fa-user',
                            'active' => request()->segment(1) == 'users',
                            // 'can' => auth()->user()->can('user.view'),
                        ],
               
                    ],
                ]
            ];

      
        // }



      

        $modifyAdminMenu = [];
      

        foreach ($menuItems as $key => $value) {

            if ($key == 'Essentials') {


                foreach ($value as $key => $value2) {


                    if ($key == 'HRM' || $key == 'أساسيات') {

                        $modifyAdminMenu[$key] = [$value2];
                    } else {

                        $modifyAdminMenu[$key] = $value2;
                    }
                }
            } else {

                $modifyAdminMenu[$key] = $value;
            }
        }
   
        $request['menuItems'] = $modifyAdminMenu;
 
        return $next($request);
    }
}
