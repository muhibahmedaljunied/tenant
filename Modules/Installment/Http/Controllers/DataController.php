<?php

namespace Modules\Installment\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class DataController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */



    public function superadmin_package()
    {
        return [
            [
                'name' => 'installment_module',
                'label' => __('installment::lang.installment_module'),
                'default' => false
            ]
        ];
    }

    /* Module menu*/
    public function modifyAdminMenu()
    {




        $menuItems = [];



        if (auth()->user()->can('installment.view')) {
            $menuItems[] = [
                'name' => __('installment::lang.installment'),
                'icon' => 'fa fa-cart-plus',
                'order' => 32,
                'sub' => [
                    [
                        'name' => __('installment::lang.installment_plan'),
                        'url' => action('\Modules\Installment\Http\Controllers\InstallmentSystemController@index'),
                        'icon' => 'fa fas fa-users-cog',
                        'active' => request()->segment(1) == 'installment' && request()->segment(2) == 'system',
                        'can' => auth()->user()->can('installment.system_add'),
                    ],
                    [
                        'name' => __('installment::lang.customer_sells'),
                        'url' => action('\Modules\Installment\Http\Controllers\SellController@index'),
                        'icon' => 'fa fas fa-users-cog',
                        'active' => request()->segment(1) == 'installment' && request()->segment(2) == 'sells',
                        'can' => auth()->user()->can('installment.create'),
                    ],
                    [
                        'name' => __('installment::lang.customer'),
                        'url' => action('\Modules\Installment\Http\Controllers\CustomerController@index'),
                        'icon' => 'fa fas fa-users-cog',
                        'active' => request()->segment(1) == 'installment' && request()->segment(2) == 'customer',
                    ],
                    [
                        'name' => __('installment::lang.installment_report'),
                        'url' => action('\Modules\Installment\Http\Controllers\InstallmentController@index'),
                        'icon' => 'fa fas fa-users-cog',
                        'active' => request()->segment(2) == 'installment',
                    ],
                    [
                        'name' => __('installment::lang.installment_customer'),
                        'url' => action('\Modules\Installment\Http\Controllers\CustomerController@contacts'),
                        'icon' => 'fa fas fa-users-cog',
                        'active' => request()->segment(2) == 'contacts',
                    ],
                ],
            ];
        }

        return $menuItems;
    }

    public function user_permissions()
    {
        return [

            [
                'value' => 'installment.view',
                'label' =>  __('installment::lang.view'),
                'default' => false
            ],

            [
                'value' => 'installment.create',
                'label' =>  __('installment::lang.create'),
                'default' => false
            ],
            [
                'value' => 'installment.edit',
                'label' => __('installment::lang.edit'),
                'default' => false
            ],
            [
                'value' => 'installment.delete',
                'label' =>  __('installment::lang.delete'),
                'default' => false
            ],

            [
                'value' => 'installment.add_Collection',
                'label' => __('installment::lang.add_Collection'),
                'default' => false
            ],

            [
                'value' => 'installment.delete_Collection',
                'label' => __('installment::lang.delete_Collection'),
                'default' => false
            ],



            [
                'value' => 'installment.system_add',
                'label' => __('installment::lang.system_add'),
                'default' => false
            ],
            [
                'value' => 'installment.system_edit',
                'label' => __('installment::lang.system_edit'),
                'default' => false
            ],
            [
                'value' => 'installment.system_delete',
                'label' => __('installment::lang.system_delete'),
                'default' => false
            ],

        ];
    }
    public function index()
    {
        return view('Installment::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('installment::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('installment::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('installment::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
