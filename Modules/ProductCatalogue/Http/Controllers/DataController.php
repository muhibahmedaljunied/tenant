<?php

namespace Modules\ProductCatalogue\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Utils\ModuleUtil;
use Menu;

class DataController extends Controller
{
    /**
     * Defines module as a superadmin package.
     * @return Array
     */
    public function superadmin_package()
    {
        return [
            [
                'name' => 'productcatalogue_module',
                'label' => __('productcatalogue::lang.productcatalogue_module'),
                'default' => false
            ]
        ];
    }

    /**
     * Adds Catalogue QR menus
     * @return null
     */
    public function modifyAdminMenu()
    {
        $business_id = session()->get('user.business_id');
        $module_util = new ModuleUtil();
        $business_id = session()->get('user.business_id');
        $is_productcatalogue_enabled = (bool) $module_util->hasThePermissionInSubscription($business_id, 'productcatalogue_module', 'superadmin_package');

        $menuItems = [];

        if ($is_productcatalogue_enabled) {
            $menuItems[] = [
                'title' => __('productcatalogue::lang.catalogue_qr'),
                'url' => action('\Modules\ProductCatalogue\Http\Controllers\ProductCatalogueController@generateQr'),
                'icon' => 'fa fas fa-qrcode',
                'active' => request()->segment(1) == 'product-catalogue',
                'style' => config('app.env') == 'demo' ? 'background-color: #ff851b;' : '',
                'order' => 95
            ];
        }

        return $menuItems;
    }
}
