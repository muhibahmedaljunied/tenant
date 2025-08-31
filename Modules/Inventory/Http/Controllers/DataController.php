<?php

namespace Modules\Inventory\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Utils\ModuleUtil;
use App\Utils\TransactionUtil;
use Menu;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class DataController extends Controller
{

    public function superadmin_package()
    {
        return [
            [
                'name' => 'inventory_module',
                'label' => __('inventory::lang.inventory'),
                'default' => false
            ]
        ];
    }

    /**
     * Display a listing of the resource.
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */

    public function modifyAdminMenu()
    {
        $business_id = session('user.business_id');
        // $module_util = new ModuleUtil;
        // $is_mfg_enabled = (boolean) $module_util->hasThePermissionInSubscription($business_id, 'inventory_module');
        // $hasPermission = auth()->user()->canAny(['inventory.stocking_create', 'inventory.stocking_edit', 'inventory.stocking_delete', 'inventory.stocking_products', 'inventory.inventory_record']);

        // if ($is_mfg_enabled && $hasPermission) {
        //     Menu::modify('admin-sidebar-menu', function ($menu) {
        //         $menu->dropdown(
        //             __('inventory::lang.inventory'),
        //             function ($sub) {
        //                 if (
        //                     auth()->user()->can('inventory.stocking_create')
        //                     || auth()->user()->can('inventory.stocking_edit')
        //                     || auth()->user()->can('inventory.stocking_delete')
        //                     || auth()->user()->can('inventory.stocking_products')
        //                 ) {
        //                     $sub->url(
        //                         action('\Modules\Inventory\Http\Controllers\InventoryController@index'),
        //                         __('inventory::lang.inventory'),
        //                         ['icon' => 'fa fas fa-user', 'active' => request()->segment(1) == 'inventory']
        //                     );
        //                 }
        //                 if (auth()->user()->can('inventory.inventory_record'))
        //                     $sub->url(
        //                         action('\Modules\Inventory\Http\Controllers\StocktackingController@details_report'),
        //                         __('inventory::lang.stock_log'),
        //                         ['icon' => 'fa fas fa-plus-circle', 'active' => request()->segment(1) == 'stocktacking' && request()->segment(2) == 'create']
        //                     );

        //             },

        //             ['icon' => 'fa fas fa-users-cog', 'style' => 'background-color: #fdfdfd !important;']
        //         )->order(35);

        //     });

        // }


        // -----------------------------------------------
        $module_util = new ModuleUtil();
        $business_id = session()->get('user.business_id');
        $is_mfg_enabled = (bool) $module_util->hasThePermissionInSubscription($business_id, 'inventory_module');
        $hasPermission = auth()->user()->canAny([
            'inventory.stocking_create',
            'inventory.stocking_edit',
            'inventory.stocking_delete',
            'inventory.stocking_products',
            'inventory.inventory_record'
        ]);

        $menuItems = [];

        if ($is_mfg_enabled && $hasPermission) {
            $inventoryMenu = [
                'title' => __('inventory::lang.inventory'),
                'icon' => 'fa fas fa-users-cog',
                'style' => 'background-color: #fdfdfd !important;',
                'order' => 35,
                'sub' => []
            ];

            if (
                auth()->user()->can('inventory.stocking_create') ||
                auth()->user()->can('inventory.stocking_edit') ||
                auth()->user()->can('inventory.stocking_delete') ||
                auth()->user()->can('inventory.stocking_products')
            ) {
                $inventoryMenu['sub'][] = [
                    'title' => __('inventory::lang.inventory'),
                    'url' => action('\Modules\Inventory\Http\Controllers\InventoryController@index'),
                    'icon' => 'fa fas fa-user',
                    'active' => request()->segment(1) == 'inventory'
                ];
            }

            if (auth()->user()->can('inventory.inventory_record')) {
                $inventoryMenu['sub'][] = [
                    'title' => __('inventory::lang.stock_log'),
                    'url' => action('\Modules\Inventory\Http\Controllers\StocktackingController@details_report'),
                    'icon' => 'fa fas fa-plus-circle',
                    'active' => request()->segment(1) == 'stocktacking' && request()->segment(2) == 'create'
                ];
            }

            $menuItems[] = $inventoryMenu;
        }

        return $menuItems;

    }

    public function index()
    {
        return view('inventory::index');
    }

    public function user_permissions()
    {
        return [
            [
                'value' => 'inventory.stocking_create', // which in database
                'label' => __('inventory::lang.stocking_create'), // use lang file in Resurces\lang\..... lang name\lang.php user value in 'creat'=>' sdhsdhsdhsdgs',
                'default' => false
            ],

            [
                'value' => 'inventory.stocking_edit',
                'label' => __('inventory::lang.stocking_edit'),
                'default' => false
            ],

            [
                'value' => 'inventory.stocking_delete',
                'label' => __('inventory::lang.stocking_delete'),
                'default' => false
            ],

            [
                'value' => 'inventory.stocking_products',
                'label' => __('inventory::lang.stocking_products'),
                'default' => false
            ],

            [
                'value' => 'inventory.showprice',
                'label' => __('inventory::lang.showprice'),
                'default' => false
            ],
            [
                'value' => 'inventory.inventory_record',
                'label' => __('inventory::lang.inventory_record'),
                'default' => false
            ]
        ];
    }

}
