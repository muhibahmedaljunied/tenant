<?php
// namespace Modules\Tracker;

namespace Modules\Tracker\Http\Controllers;

use App\Utils\ModuleUtil;
use Illuminate\Routing\Controller;
use Nwidart\Menus\Facades\Menu;

class DataController extends Controller
{
    public function modifyAdminMenu()
    {
        $business_id = session()->get('user.business_id');
        // $module_util = new ModuleUtil();
        // $is_enabled = $module_util->hasThePermissionInSubscription($business_id, 'tracker_module');
        // $has_access = auth()->user()->canAny(['tracker.view_provinces','tracker.view_sectors','tracker.view_distribution_areas','tracker.view_tracks', 'tracker.access_map']);
        // if($is_enabled && $has_access) {
        //     $menu = Menu::instance('admin-sidebar-menu');
        //     $menu->dropdown(
        //         __('tracker::lang.DelegatesManagement'),
        //         function ($sub) {
        //             if(auth()->user()->can('tracker.view_provinces')){
        //                 $sub->url(
        //                     action('\Modules\Tracker\Http\Controllers\ProvinceController@index'),
        //                     __('tracker::lang.Provinces'),
        //                     ['active' => request()->routeIs('dm.provinces.index')]
        //                 );
        //             }

        //             if(auth()->user()->can('tracker.view_sectors')) {
        //                 $sub->url(
        //                     action('\Modules\Tracker\Http\Controllers\SectorController@index'),
        //                     __('tracker::lang.Sectors'),
        //                     ['active' => request()->routeIs('dm.sectors.index')],
        //                 );
        //             }

        //             if(auth()->user()->can('tracker.view_distribution_areas')) {
        //                 $sub->url(
        //                     action('\Modules\Tracker\Http\Controllers\DistributionAreaController@index'),
        //                     __('tracker::lang.DistributionAreas'),
        //                     ['active' => request()->routeIs('dm.distributionAreas.index')]
        //                 );
        //             }

        //             if(auth()->user()->can('tracker.view_tracks')) {
        //                 $sub->url(
        //                     action('\Modules\Tracker\Http\Controllers\TrackController@index'),
        //                     __('tracker::lang.Tracks'),
        //                     ['active' => request()->routeIs('dm.tracks.index')]
        //                 );
        //             }

        //             if(auth()->user()->can('tracker.access_map')) {
        //                 $sub->url(
        //                     action('\Modules\Tracker\Http\Controllers\ContactController@map'),
        //                     __('tracker::lang.ContactsMap'),
        //                     ['active' => request()->routeIs('dm.contacts.map')]
        //                 );
        //             }
        //         },
        //         ['icon' => 'fa fas fa-id-badge']
        //     )->order(19);
        // }


        // -----------------------------------------
        $module_util = new ModuleUtil();
        $business_id = session()->get('user.business_id');
        $is_enabled = $module_util->hasThePermissionInSubscription($business_id, 'tracker_module');
        $has_access = auth()->user()->canAny([
            'tracker.view_provinces',
            'tracker.view_sectors',
            'tracker.view_distribution_areas',
            'tracker.view_tracks',
            'tracker.access_map'
        ]);

        $menuItems = [];

        if ($is_enabled && $has_access) {
            $trackerMenu = [
                'title' => __('tracker::lang.DelegatesManagement'),
                'icon' => 'fa fas fa-id-badge',
                'order' => 19,
                'sub' => []
            ];

            if (auth()->user()->can('tracker.view_provinces')) {
                $trackerMenu['sub'][] = [
                    'title' => __('tracker::lang.Provinces'),
                    'url' => action('\Modules\Tracker\Http\Controllers\ProvinceController@index'),
                    'active' => request()->routeIs('dm.provinces.index')
                ];
            }

            if (auth()->user()->can('tracker.view_sectors')) {
                $trackerMenu['sub'][] = [
                    'title' => __('tracker::lang.Sectors'),
                    'url' => action('\Modules\Tracker\Http\Controllers\SectorController@index'),
                    'active' => request()->routeIs('dm.sectors.index')
                ];
            }

            if (auth()->user()->can('tracker.view_distribution_areas')) {
                $trackerMenu['sub'][] = [
                    'title' => __('tracker::lang.DistributionAreas'),
                    'url' => action('\Modules\Tracker\Http\Controllers\DistributionAreaController@index'),
                    'active' => request()->routeIs('dm.distributionAreas.index')
                ];
            }

            if (auth()->user()->can('tracker.view_tracks')) {
                $trackerMenu['sub'][] = [
                    'title' => __('tracker::lang.Tracks'),
                    'url' => action('\Modules\Tracker\Http\Controllers\TrackController@index'),
                    'active' => request()->routeIs('dm.tracks.index')
                ];
            }

            if (auth()->user()->can('tracker.access_map')) {
                $trackerMenu['sub'][] = [
                    'title' => __('tracker::lang.ContactsMap'),
                    'url' => action('\Modules\Tracker\Http\Controllers\ContactController@map'),
                    'active' => request()->routeIs('dm.contacts.map')
                ];
            }

            $menuItems[] = $trackerMenu;
        }

        return $menuItems;
    }

    public function contact_form_part()
    {
        return  [
            'template_path' => 'tracker::partial.contact_form_part',
            'template_data' => []
        ];
    }


    public function user_permissions()
    {
        return [
            [
                'value' => 'tracker.access_map',
                'label' => __('tracker::lang.access_map'),
                'default' => false
            ],
            [
                'value' => 'tracker.view_provinces',
                'label' => __('tracker::lang.view_provinces'),
                'default' => false,
            ],
            [
                'value' => 'tracker.create_provinces',
                'label' => __('tracker::lang.create_provinces'),
                'default' => false
            ],
            [
                'value' => 'tracker.edit_provinces',
                'label' => __('tracker::lang.edit_provinces'),
                'default' => false,
            ],
            [
                'value' => 'tracker.delete_provinces',
                'label' => __('tracker::lang.delete_provinces'),
                'default' => false
            ],
            [
                'value' => 'tracker.view_sectors',
                'label' => __('tracker::lang.view_sectors'),
                'default' => false
            ],
            [
                'value' => 'tracker.create_sectors',
                'label' => __('tracker::lang.create_sectors'),
                'default' => false
            ],
            [
                'value' => 'tracker.edit_sectors',
                'label' => __('tracker::lang.edit_sectors'),
                'default' => false
            ],
            [
                'value' => 'tracker.delete_sectors',
                'label' => __('tracker::lang.delete_sectors'),
                'default' => false
            ],
            [
                'value' => 'tracker.view_distribution_areas',
                'label' => __('tracker::lang.view_distribution_areas'),
                'default' => false
            ],
            [
                'value' => 'tracker.create_distribution_areas',
                'label' => __('tracker::lang.create_distribution_areas'),
                'default' => false
            ],
            [
                'value' => 'tracker.edit_distribution_areas',
                'label' => __('tracker::lang.edit_distribution_areas'),
                'default' => false
            ],
            [
                'value' => 'tracker.delete_distribution_areas',
                'label' => __('tracker::lang.delete_distribution_areas'),
                'default' => false
            ],
            [
                'value' => 'tracker.view_tracks',
                'label' => __('tracker::lang.view_tracks'),
                'default' => false
            ],
            [
                'value' => 'tracker.create_tracks',
                'label' => __('tracker::lang.create_tracks'),
                'default' => false
            ],
            [
                'value' => 'tracker.edit_tracks',
                'label' => __('tracker::lang.edit_tracks'),
                'default' => false
            ],
            [
                'value' => 'tracker.delete_tracks',
                'label' => __('tracker::lang.delete_tracks'),
                'default' => false
            ]
        ];
    }

    public function after_contact_saved($data)
    {
        $contact = $data['contact'];
        $input = $data['input'];

        if (isset($input['location'])) {
            $contact->location = $input['location'];
            $contact->save();
        }
    }

    /**
     * Superadmin package permissions
     * @return array
     */
    public function superadmin_package()
    {
        return [
            [
                'name'      => 'tracker_module',
                'label'     => __('tracker::lang.tracker_module'),
                'default'   => true
            ]
        ];
    }
}
