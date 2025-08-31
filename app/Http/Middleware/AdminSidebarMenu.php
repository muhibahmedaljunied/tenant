<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Zatca;
use App\Utils\ModuleUtil;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class AdminSidebarMenu
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
                'url' => action('HomeController@index'),
                'icon' => 'fa fas fa-tachometer-alt',
                'active' => request()->segment(1) == 'home',
                'order' => 5,
            ]
        ];


        // dd(auth()->user()->hasRole('Admin#' . session('business.id')));
        // $user = Auth::user();
        // $user = User::find($user->id);
        $is_admin = auth()->user()->hasRole('Admin#' . session('business.id'));
        $enabled_modules = !empty(session('business.enabled_modules')) ? session('business.enabled_modules') : [];

        if (auth()->user()->can('user.view') || auth()->user()->can('user.create') || auth()->user()->can('roles.view')) {

            $menuItems[__('user.user_management')] = [
                [
                    'name' => __('user.user_management'),
                    'icon' => 'fa fas fa-users',
                    'order' => 10,
                    'sub' => [
                        [
                            'name' => __('user.users'),
                            'url' => action('ManageUserController@index'),
                            'icon' => 'fa fas fa-user',
                            'active' => request()->segment(1) == 'users',
                            'can' => auth()->user()->can('user.view'),
                        ],
                        [
                            'name' => __('user.roles'),
                            'url' => action('RoleController@index'),
                            'icon' => 'fa fas fa-briefcase',
                            'active' => request()->segment(1) == 'roles',
                            'can' => auth()->user()->can('roles.view'),
                        ],
                        [
                            'name' => __('lang_v1.sales_commission_agents'),
                            'url' => action('SalesCommissionAgentController@index'),
                            'icon' => 'fa fas fa-handshake',
                            'active' => request()->segment(1) == 'sales-commission-agents',
                            'can' => auth()->user()->can('user.create'),
                        ],
                    ],
                ]
            ];
        }

   

        $is_admin = auth()->user()->hasRole('Admin#' . session('business.id'));
        $enabled_modules = !empty(session('business.enabled_modules')) ? session('business.enabled_modules') : [];

        if (auth()->user()->can('supplier.view') || auth()->user()->can('customer.view')) {


            $menuItems[__('contact.contacts')] = [
                [
                    'name' => __('contact.contacts'),
                    'icon' => 'fa fas fa-address-book',
                    'id' => "tour_step4",
                    'order' => 15,
                    'sub' => [
                        [
                            'name' => __('report.supplier'),
                            'url' => action('ContactController@index', ['type' => 'supplier']),
                            'icon' => 'fa fas fa-star',
                            'active' => request()->input('type') == 'supplier',
                            'can' => auth()->user()->can('supplier.view'),
                        ],
                        [
                            'name' => __('report.customer'),
                            'url' => action('ContactController@index', ['type' => 'customer']),
                            'icon' => 'fa fas fa-star',
                            'active' => request()->input('type') == 'customer',
                            'can' => auth()->user()->can('customer.view'),
                        ],
                        [
                            'name' => __('lang_v1.customer_groups'),
                            'url' => action('CustomerGroupController@index'),
                            'icon' => 'fa fas fa-users',
                            'active' => request()->segment(1) == 'customer-group',
                            'can' => auth()->user()->can('customer.view'),
                        ],
                        [
                            'name' => __('lang_v1.import_contacts'),
                            'url' => action('ContactController@getImportContacts'),
                            'icon' => 'fa fas fa-download',
                            'active' => request()->segment(1) == 'contacts' && request()->segment(2) == 'import',
                            'can' => auth()->user()->can('supplier.create') || auth()->user()->can('customer.create'),
                        ],
                        [
                            'name' => __('lang_v1.map'),
                            'url' => action('ContactController@contactMap'),
                            'icon' => 'fa fas fa-map-marker-alt',
                            'active' => request()->segment(1) == 'contacts' && request()->segment(2) == 'map',
                            'can' => !empty(env('GOOGLE_MAP_API_KEY')),
                        ],
                    ]
                ],
            ];
        }

        if (
            auth()->user()->can('product.view') || auth()->user()->can('product.create') ||
            auth()->user()->can('brand.view') || auth()->user()->can('unit.view') ||
            auth()->user()->can('category.view') || auth()->user()->can('brand.create') ||
            auth()->user()->can('unit.create') || auth()->user()->can('category.create') ||
            auth()->user()->can('store.create') || auth()->user()->can('category.create')
        ) {

            $menuItems[__('sale.products')] = [

                [
                    'name' => __('sale.products'),
                    'icon' => 'fa fas fa-cubes',
                    'id' => 'tour_step5',
                    'order' => 20,
                    'sub' => [
                        [
                            'name' => __('lang_v1.list_products'),
                            'url' => action('ProductController@index'),
                            'icon' => 'fa fas fa-list',
                            'active' => request()->segment(1) == 'products' && request()->segment(2) == '',
                            'can' => auth()->user()->can('product.view'),
                        ],
                        [
                            'name' => __('product.add_product'),
                            'url' => action('ProductController@create'),
                            'icon' => 'fa fas fa-plus-circle',
                            'active' => request()->segment(1) == 'products' && request()->segment(2) == 'create',
                            'can' => auth()->user()->can('product.create'),
                        ],
                        [
                            'name' => __('barcode.print_labels'),
                            'url' => action('LabelsController@show', ['product_id=null']),
                            'icon' => 'fa fas fa-barcode',
                            'active' => request()->segment(1) == 'labels' && request()->segment(2) == 'show',
                            'can' => auth()->user()->can('product.view'),
                        ],
                        [
                            'name' => __('product.variations'),
                            'url' => action('VariationTemplateController@index'),
                            'icon' => 'fa fas fa-circle',
                            'active' => request()->segment(1) == 'variation-templates',
                            'can' => auth()->user()->can('product.create'),
                        ],
                        [
                            'name' => __('product.import_products'),
                            'url' => action('ImportProductsController@index'),
                            'icon' => 'fa fas fa-download',
                            'active' => request()->segment(1) == 'import-products',
                            'can' => auth()->user()->can('product.create'),
                        ],
                        [
                            'name' => __('lang_v1.import_opening_stock'),
                            'url' => action('ImportOpeningStockController@index'),
                            'icon' => 'fa fas fa-download',
                            'active' => request()->segment(1) == 'import-opening-stock',
                            'can' => auth()->user()->can('product.opening_stock'),
                        ],
                        [
                            'name' => __('lang_v1.selling_price_group'),
                            'url' => action('SellingPriceGroupController@index'),
                            'icon' => 'fa fas fa-circle',
                            'active' => request()->segment(1) == 'selling-price-group',
                            'can' => auth()->user()->can('product.create'),
                        ],
                        [
                            'name' => __('unit.units'),
                            'url' => action('UnitController@index'),
                            'icon' => 'fa fas fa-balance-scale',
                            'active' => request()->segment(1) == 'units',
                            'can' => auth()->user()->can('unit.view') || auth()->user()->can('unit.create'),
                        ],
                        [
                            'name' => __('store.stores'),
                            'url' => action('StoreController@index'),
                            'icon' => 'fa fas fa-balance-scale',
                            'active' => request()->segment(1) == 'stores',
                            'can' => auth()->user()->can('store.view') || auth()->user()->can('store.create'),
                        ],
                        [
                            'name' => __('category.categories'),
                            'url' => action('TaxonomyController@index') . '?type=product',
                            'icon' => 'fa fas fa-tags',
                            'active' => request()->segment(1) == 'taxonomies' && request()->get('type') == 'product',
                            'can' => auth()->user()->can('category.view') || auth()->user()->can('category.create'),
                        ],
                        [
                            'name' => __('brand.brands'),
                            'url' => action('BrandController@index'),
                            'icon' => 'fa fas fa-gem',
                            'active' => request()->segment(1) == 'brands',
                            'can' => auth()->user()->can('brand.view') || auth()->user()->can('brand.create'),
                        ],
                        [
                            'name' => __('lang_v1.warranties'),
                            'url' => action('WarrantyController@index'),
                            'icon' => 'fa fas fa-shield-alt',
                            'active' => request()->segment(1) == 'warranties',
                        ],
                    ],
                ]
            ];
        }



        $is_admin = auth()->user()->hasRole('Admin#' . session('business.id'));
        $enabled_modules = !empty(session('business.enabled_modules')) ? session('business.enabled_modules') : [];

        if (auth()->user()->can('product.gallary')) {

            $menuItems[__('lang_v1.product_gallery')] = [
                [
                    'name' => __('lang_v1.product_gallery'),
                    'icon' => 'fa fas fa-truck',
                    'order' => 21,
                    'sub' => [
                        [
                            'name' => __('lang_v1.product_gallery'),
                            'url' => action('ProductGallery@gallery'),
                            'icon' => 'fa fas fa-list',
                            'active' => request()->segment(1) == 'gallery' && request()->segment(2) == 'gallery',
                        ],
                        [
                            'name' => __('report.stock_report'),
                            'url' => action('ProductGallery@stock_report'),
                            'icon' => 'fa fas fa-list',
                            'active' => request()->segment(1) == 'gallery' && request()->segment(2) == 'stock_report',
                        ],
                        [
                            'name' => __('lang_v1.gallery_setting'),
                            'url' => action('ProductGallery@setting'),
                            'icon' => 'fa fas fa-plus-circle',
                            'active' => request()->segment(1) == 'gallery' && request()->segment(2) == 'setting',
                        ],
                    ],
                ]
            ];
        }

        if (in_array('purchases', $enabled_modules) && (auth()->user()->can('purchase.view') || auth()->user()->can('purchase.create') || auth()->user()->can('purchase.update'))) {

            $menuItems[__('purchase.purchases')] = [
                [
                    'name' => __('purchase.purchases'),
                    'icon' => 'fa fas fa-arrow-circle-down',
                    'id' => 'tour_step6',
                    'order' => 25,
                    'sub' => [
                        [
                            'name' => __('purchase.list_purchase'),
                            'url' => action('PurchaseController@index'),
                            'icon' => 'fa fas fa-list',
                            'active' => request()->segment(1) == 'purchases' && request()->segment(2) == null,
                            'can' => auth()->user()->can('purchase.view') || auth()->user()->can('view_own_purchase'),
                        ],
                        [
                            'name' => __('purchase.add_purchase'),
                            'url' => action('PurchaseController@create'),
                            'icon' => 'fa fas fa-plus-circle',
                            'active' => request()->segment(1) == 'purchases' && request()->segment(2) == 'create',
                            'can' => auth()->user()->can('purchase.create'),
                        ],
                        [
                            'name' => __('lang_v1.list_purchase_return'),
                            'url' => action('PurchaseReturnController@index'),
                            'icon' => 'fa fas fa-undo',
                            'active' => request()->segment(1) == 'purchase-return',
                            'can' => auth()->user()->can('purchase_return.view'),
                        ],
                        [
                            'name' => __('مرتجع مشتريات'),
                            'url' => action('ReportController@getproductPurchaseReport'),
                            'icon' => 'fa fas fa-undo',
                            'active' => request()->segment(2) == 'product-purchase-report',
                            'can' => auth()->user()->can('purchase_return.create'),
                        ],
                    ],
                ]
            ];
        }



        $is_admin = auth()->user()->hasRole('Admin#' . session('business.id'));
        $enabled_modules = !empty(session('business.enabled_modules')) ? session('business.enabled_modules') : [];

        if ($is_admin || $user->hasAnyPermission([
            'sell.view',
            'sell.create',
            'direct_sell.access',
            'view_own_sell_only',
            'view_commission_agent_sell',
            'access_shipping',
            'access_own_shipping',
            'access_commission_agent_shipping',
            'access_sell_return'
        ])) {

            $menuItems[__('sale.sale')] = [
                [
                    'name' => __('sale.sale'),
                    'icon' => 'fa fa-registered',
                    'id' => 'tour_step7',
                    'order' => 30,
                    'sub' => [
                        [
                            'name' => __('lang_v1.all_sales'),
                            'url' => action('SellController@index'),
                            'icon' => 'fa fas fa-list',
                            'active' => request()->segment(1) == 'sells' && request()->segment(2) == null,
                            'can' => $is_admin || $user->hasAnyPermission([
                                'sell.view',
                                'sell.create',
                                'direct_sell.access',
                                'view_own_sell_only',
                                'view_commission_agent_sell',
                                'access_shipping',
                                'access_own_shipping',
                                'access_commission_agent_shipping'
                            ]),
                        ],
                        [
                            'name' => __('sale.add_sale'),
                            'url' => action('SellController@create'),
                            'icon' => 'fa fas fa-plus-circle',
                            'active' => request()->segment(1) == 'sells' && request()->segment(2) == 'create' && empty(request()->get('status')),
                            'can' => in_array('add_sale', $enabled_modules) && auth()->user()->can('direct_sell.access'),
                        ],
                        [
                            'name' => __('sale.list_pos'),
                            'url' => action('SellPosController@index'),
                            'icon' => 'fa fas fa-list',
                            'active' => request()->segment(1) == 'pos' && request()->segment(2) == null,
                            'can' => auth()->user()->can('sell.view'),
                        ],
                        [
                            'name' => __('sale.pos_sale'),
                            'url' => action('SellPosController@create'),
                            'icon' => 'fa fas fa-plus-circle',
                            'active' => request()->segment(1) == 'pos' && request()->segment(2) == 'create',
                            'can' => in_array('pos_sale', $enabled_modules) && auth()->user()->can('sell.create'),
                        ],
                        [
                            'name' => __('lang_v1.list_sell_return'),
                            'url' => action('SellReturnController@index'),
                            'icon' => 'fa fas fa-undo',
                            'active' => request()->segment(1) == 'sell-return' && request()->segment(2) == null,
                            'can' => auth()->user()->can('access_sell_return'),
                        ],
                        [
                            'name' => __('lang_v1.shipments'),
                            'url' => action('SellController@shipments'),
                            'icon' => 'fa fas fa-truck',
                            'active' => request()->segment(1) == 'shipments',
                            'can' => $is_admin || $user->hasAnyPermission([
                                'access_shipping',
                                'access_own_shipping',
                                'access_commission_agent_shipping'
                            ]),
                        ],
                        [
                            'name' => __('lang_v1.discounts'),
                            'url' => action('DiscountController@index'),
                            'icon' => 'fa fas fa-percent',
                            'active' => request()->segment(1) == 'discount',
                            'can' => auth()->user()->can('discount.access'),
                        ],
                        [
                            'name' => __('lang_v1.subscriptions'),
                            'url' => action('SellPosController@listSubscriptions'),
                            'icon' => 'fa fas fa-recycle',
                            'active' => request()->segment(1) == 'subscriptions',
                            'can' => in_array('subscription', $enabled_modules) && auth()->user()->can('direct_sell.access'),
                        ],
                        [
                            'name' => __('lang_v1.import_sales'),
                            'url' => action('ImportSalesController@index'),
                            'icon' => 'fa fas fa-file-import',
                            'active' => request()->segment(1) == 'import-sales',
                            'can' => auth()->user()->can('sell.create'),
                        ],
                    ],
                ]
            ];
        }



        if (in_array('kitchen', $enabled_modules)) {

            $menuItems[__('restaurant.restaurant_managment')] = [
                [
                    'name' => __('restaurant.restaurant_managment'),
                    'icon' => 'fa fa-registered',
                    'id' => 'tour_step7',
                    'order' => 35,
                    'sub' => [
                        [
                            'name' => __('restaurant.kitchen_order'),
                            'url' => action('Restaurant\KitchenController@index_order'),
                            'icon' => 'fa fas fa-list',
                            'active' => request()->segment(1) == 'modules' && request()->segment(2) == 'kitchen',
                            'can' => auth()->user()->can('purchase.view') || auth()->user()->can('view_own_purchase'),
                        ],
                        [
                            'name' => __('restaurant.orders'),
                            'url' => action('Restaurant\OrderController@index'),
                            'icon' => 'fa fas fa-list',
                        ],
                        [
                            'name' => __('restaurant.bookings'),
                            'url' => action('Restaurant\BookingController@index'),
                            'icon' => 'fas fa fa-cutlery',
                            'active' => request()->segment(1) == 'bookings',
                        ],
                        [
                            'name' => __('restaurant.table_report'),
                            'url' => action('ReportController@getTableReport'),
                            'icon' => 'fas fa fa-cutlery',
                            'active' => request()->segment(2) == 'table-report',
                        ],
                        [
                            'name' => __('restaurant.service_staff_report'),
                            'url' => action('ReportController@getServiceStaffReport'),
                            'icon' => 'fa fas fa-user-secret',
                            'active' => request()->segment(2) == 'service-staff-report',
                        ],
                        [
                            'name' => __('restaurant.tables'),
                            'url' => action('Restaurant\TableController@index'),
                            'icon' => 'fa fas fa-table',
                            'active' => request()->segment(1) == 'modules' && request()->segment(2) == 'tables',
                        ],
                        [
                            'name' => __('restaurant.modifiers'),
                            'url' => action('Restaurant\ModifierSetsController@index'),
                            'icon' => 'fa fas fa-pizza-slice',
                            'active' => request()->segment(1) == 'modules' && request()->segment(2) == 'modifiers',
                            'can' => auth()->user()->can('product.view') || auth()->user()->can('product.create'),
                        ],
                        [
                            'name' => __('restaurant.restaurant_department'),
                            'url' => action('Restaurant\KitchenController@index'),
                            'icon' => 'fa fas fa-table',
                            'active' => request()->segment(1) == 'modules' && request()->segment(2) == 'kitchen',
                        ],
                        [
                            'name' => 'أصناف المطبخ',
                            'url' => action('Restaurant\KitchenController@products'),
                            'icon' => 'fa fas fa-map-marker',
                            'active' => request()->segment(2) == 'kitchen_products',
                            'can' => auth()->user()->can('kitchen.create'),
                        ],
                    ],
                ]
            ];
        }



        if (in_array('stock_transfers', $enabled_modules) && (auth()->user()->can('purchase.view') || auth()->user()->can('purchase.create'))) {

            $menuItems[__('lang_v1.stock_transfers')] = [
                [
                    'name' => __('lang_v1.stock_transfers'),
                    'icon' => 'fa fas fa-truck',
                    'order' => 35,
                    'sub' => [
                        [
                            'name' => __('lang_v1.list_stock_transfers'),
                            'url' => action('StockTransferController@index'),
                            'icon' => 'fa fas fa-list',
                            'active' => request()->segment(1) == 'stock-transfers' && request()->segment(2) == null,
                            'can' => auth()->user()->can('purchase.view'),
                        ],
                        [
                            'name' => __('lang_v1.add_stock_transfer'),
                            'url' => action('StockTransferController@create'),
                            'icon' => 'fa fas fa-plus-circle',
                            'active' => request()->segment(1) == 'stock-transfers' && request()->segment(2) == 'create',
                            'can' => auth()->user()->can('purchase.create'),
                        ],
                    ],
                ]
            ];
        }

        if (in_array('stock_adjustment', $enabled_modules) && (auth()->user()->can('purchase.view') || auth()->user()->can('purchase.create'))) {

            $menuItems[__('stock_adjustment.stock_adjustment')] = [
                [
                    'name' => __('stock_adjustment.stock_adjustment'),
                    'icon' => 'fa fas fa-database',
                    'order' => 40,
                    'sub' => [
                        [
                            'name' => __('stock_adjustment.list'),
                            'url' => action('StockAdjustmentController@index'),
                            'icon' => 'fa fas fa-list',
                            'active' => request()->segment(1) == 'stock-adjustments' && request()->segment(2) == null,
                            'can' => auth()->user()->can('purchase.view'),
                        ],
                        [
                            'name' => __('stock_adjustment.add'),
                            'url' => action('StockAdjustmentController@create'),
                            'icon' => 'fa fas fa-plus-circle',
                            'active' => request()->segment(1) == 'stock-adjustments' && request()->segment(2) == 'create',
                            'can' => auth()->user()->can('purchase.create'),
                        ],
                    ],
                ]
            ];
        }



        if (session('business.zatca_phase') === Zatca::STATUS_PHASE2) {

            $menuItems[__('zatca.zatca')] = [
                [
                    'name' => __('zatca.zatca'),
                    'icon' => 'fa fa-inbox',
                    'order' => 38,
                    'sub' => [
                        [
                            'name' => __('zatca.invoices.title'),
                            'url' => route('zatca.invoices.index'),
                            'active' => request()->routeIs('zatca.invoices.*'),
                        ],
                    ],
                ]
            ];
        }

        if (in_array('expenses', $enabled_modules) && (auth()->user()->can('expenses.view'))) {

            $menuItems[__('expense.expenses')] = [
                [
                    'name' => __('expense.expenses'),
                    'icon' => 'fa fas fa-minus-circle',
                    'order' => 45,
                    'sub' => [
                        [
                            'name' => __('lang_v1.list_expenses'),
                            'url' => action('ExpenseController@index'),
                            'icon' => 'fa fas fa-list',
                            'active' => request()->segment(1) == 'expenses' && request()->segment(2) == null,
                            'can' => auth()->user()->can('expenses.view'),
                        ],
                        [
                            'name' => __('expense.add_expense'),
                            'url' => action('ExpenseController@create'),
                            'icon' => 'fa fas fa-plus-circle',
                            'active' => request()->segment(1) == 'expenses' && request()->segment(2) == 'create',
                            'can' => auth()->user()->can('expenses.create'),
                        ],
                        [
                            'name' => __('expense.expense_categories'),
                            'url' => action('ExpenseCategoryController@index'),
                            'icon' => 'fa fas fa-circle',
                            'active' => request()->segment(1) == 'expense-categories',
                            'can' => auth()->user()->can('expenses.categories'),
                        ],
                    ],
                ]
            ];
        }



        if (auth()->user()->can('account.access') && in_array('account', $enabled_modules)) {

            $menuItems[__('lang_v1.payment_accounts')] =
                [
                    [
                        'name' => __('lang_v1.payment_accounts'),
                        'icon' => 'fa fas fa-money-check-alt',
                        'order' => 50,
                        'sub' => [
                            [
                                'name' => __('account.list_accounts'),
                                'url' => action('AccountController@index'),
                                'icon' => 'fa fas fa-list',
                                'active' => request()->segment(1) == 'account' && request()->segment(2) == 'account',
                            ],
                            [
                                'name' => __('account.balance_sheet'),
                                'url' => action('AccountReportsController@balanceSheet'),
                                'icon' => 'fa fas fa-book',
                                'active' => request()->segment(1) == 'account' && request()->segment(2) == 'balance-sheet',
                            ],
                            [
                                'name' => __('account.trial_balance'),
                                'url' => action('AccountReportsController@trialBalance'),
                                'icon' => 'fa fas fa-balance-scale',
                                'active' => request()->segment(1) == 'account' && request()->segment(2) == 'trial-balance',
                            ],
                            [
                                'name' => __('lang_v1.cash_flow'),
                                'url' => action('AccountController@cashFlow'),
                                'icon' => 'fa fas fa-exchange-alt',
                                'active' => request()->segment(1) == 'account' && request()->segment(2) == 'cash-flow',
                            ],
                            [
                                'name' => __('account.payment_account_report'),
                                'url' => action('AccountReportsController@paymentAccountReport'),
                                'icon' => 'fa fas fa-file-alt',
                                'active' => request()->segment(1) == 'account' && request()->segment(2) == 'payment-account-report',
                            ],
                        ],
                    ]
                ];
        }

        if (auth()->user()->can('chartofaccounts.view') && in_array('account', $enabled_modules)) {


            $menuItems[__('chart_of_accounts.account_menu')] =
                [

                    [
                        'name' => __('chart_of_accounts.account_menu'),
                        'icon' => 'fa fas fa-money-check-alt',
                        'order' => 8,
                        'sub' => [
                            [
                                'name' => __('chart_of_accounts.list_accounts'),
                                'url' => action('AcMasterController@index'),
                                'icon' => 'fa fas fa-list',
                                'active' => request()->segment(1) == 'ac' && request()->segment(2) == 'master',
                            ],
                            auth()->user()->can('branch_cost_center.view') ? [
                                'name' => __('chart_of_accounts.cost_cen_branch'),
                                'url' => action('AcCostCenBrancheController@index'),
                                'icon' => 'fa fas fa-list',
                                'active' => request()->segment(1) == 'ac' && request()->segment(2) == 'cost_cen_branche',
                            ] : null,
                            auth()->user()->can('extra_cost_center.view') ? [
                                'name' => __('chart_of_accounts.cost_cen_field'),
                                'url' => action('AcCostCenFieldAddController@index'),
                                'icon' => 'fa fas fa-list',
                                'active' => request()->segment(1) == 'ac' && request()->segment(2) == 'cost_cen_field_add',
                            ] : null,
                            auth()->user()->can('journal_entry.view') ?
                                [
                                    'name' => __('chart_of_accounts.journal_entries'),
                                    'url' => action('AcJournalEntryController@index'),
                                    'icon' => 'fa fas fa-list',
                                    'active' => request()->segment(1) == 'ac' && request()->segment(2) == 'journal_entry',
                                ]

                                : null,
                            auth()->user()->can('journal_entry.view') ?

                                [
                                    'name' => __('chart_of_accounts.opening_entries'),
                                    'url' => action('OpeningEntryController@index'),
                                    'icon' => 'fa fas fa-list',
                                    'active' => request()->segment(1) == 'ac' && request()->segment(2) == 'opening_entries',
                                ]
                                : null,
                            auth()->user()->can('journal_ledger.access') ? [
                                'name' => __('chart_of_accounts.journal_ledger'),
                                'url' => action('AcReportController@JournalLedgerReport'),
                                'icon' => 'fa fas fa-list',
                                'active' => request()->segment(1) == 'ac' && request()->segment(2) == 'reports' && request()->segment(3) == 'journal-ledger-report',
                            ] : null,
                            auth()->user()->can('account_statement.access') ?
                                [
                                    'name' => __('chart_of_accounts.account_statement'),
                                    'url' => action('AcReportController@AccountStatementReport'),
                                    'icon' => 'fa fas fa-list',
                                    'active' => request()->segment(1) == 'ac' && request()->segment(2) == 'reports' && request()->segment(3) == 'account-statement-report',
                                ]

                                : null,
                            auth()->user()->can('account_statement.access') ?

                                [
                                    'name' => __('chart_of_accounts.aging_debts_suppliers'),
                                    'url' => action('AcReportController@AccountStatementReportSupplier'),
                                    'icon' => 'fa fas fa-list',
                                    'active' => request()->segment(1) == 'ac' && request()->segment(2) == 'reports' && request()->segment(3) == 'account-statement-report-supplier',
                                ]

                                : null,
                            auth()->user()->can('account_statement.access') ?

                                [
                                    'name' => __('chart_of_accounts.aging_debts_clients'),
                                    'url' => action('AcReportController@AccountStatementReportClient'),
                                    'icon' => 'fa fas fa-list',
                                    'active' => request()->segment(1) == 'ac' && request()->segment(2) == 'reports' && request()->segment(3) == 'account-statement-report-client',
                                ]
                                : null,

                            auth()->user()->can('trial_balance.access') ?
                                [
                                    'name' => __('chart_of_accounts.trial_balance'),
                                    'url' => action('AcReportController@TrialBalanceReport'),
                                    'icon' => 'fa fas fa-list',
                                    'active' => request()->segment(1) == 'ac' && request()->segment(2) == 'reports' && request()->segment(3) == 'trial-balance-report'
                                ] : null,


                            auth()->user()->can('income_statement.access') ? [
                                'name' => __('chart_of_accounts.income_statement'),
                                'url' => action('AcReportController@IncomeStatementReport'),
                                'icon' => 'fa fas fa-list',
                                'active' => request()->segment(1) == 'ac' && request()->segment(2) == 'reports' && request()->segment(3) == 'income-statement-report',
                            ] : null,

                            auth()->user()->can('balance_sheet.access') ?
                                [
                                    'name' => __('chart_of_accounts.balance_sheet'),
                                    'url' => action('AcReportController@BalanceSheetReport'),
                                    'icon' => 'fa fas fa-list',
                                    'active' => request()->segment(1) == 'ac' && request()->segment(2) == 'reports' && request()->segment(3) == 'balance-sheet-report',
                                ] : null,

                            auth()->user()->can('accounts_routing.access') ?
                                [
                                    'name' => __('chart_of_accounts.accounts_routing'),
                                    'url' => action('AcRoutingAccountsController@index'),
                                    'icon' => 'fa fas fa-list',
                                    'active' => request()->segment(1) == 'ac' && request()->segment(2) == 'accounts_routing',
                                ] : null,

                            [
                                'name' => __('chart_of_accounts.import_master'),
                                'url' => action('AcMaster\ImportAcMasterController@index'),
                                'icon' => 'fa fas fa-list',
                                'active' => request()->routeIs('ac.import-master.index'),
                            ],
                            [
                                'name' => __('chart_of_accounts.import_journal_entry'),
                                'url' => action('AcJournalEntryController@importView'),
                                'icon' => 'fa fas fa-list',
                                'active' => request()->routeIs('ac_journal.import.view'),
                            ],

                            [
                                'name' => __('chart_of_accounts.periods'),
                                'url' => action('PeriodController@index'),
                                'icon' => 'fa fas fa-list',
                                'active' => request()->routeIs('ac_journal.import.view'),
                            ]
                        ]
                    ]
                ];


            $menuItems[__('assets.fixed_asset_menu')] =
                [
                    [
                        'name' => __('assets.fixed_asset_menu'),
                        'icon' => 'fa fas fa-money-check-alt',
                        'order' => 9,
                        'sub' => [
                            [
                                'name' => __('assets.asset_classes'),
                                'url' => action('AcAssetClassController@index'),
                                'icon' => 'fa fas fa-list',
                                'active' => request()->segment(1) == 'ac' && request()->segment(2) == 'class_asset',
                            ],
                            [
                                'name' => __('assets.assets'),
                                'url' => action('AcAssetController@index'),
                                'icon' => 'fa fas fa-list',
                                'active' => request()->segment(1) == 'ac' && request()->segment(2) == 'asset',
                            ],
                            [
                                'name' => __('depreciation.depreciations'),
                                'url' => action('DepreciationController@index'),
                                'icon' => 'fa fas fa-list',
                            ],
                        ],
                    ]
                ];
        }


        // dd($menuItems);
        if (
            auth()->user()->can('purchase_n_sell_report.view') || auth()->user()->can('contacts_report.view')
            || auth()->user()->can('stock_report.view') || auth()->user()->can('tax_report.view')
            || auth()->user()->can('trending_product_report.view') || auth()->user()->can('sales_representative.view') || auth()->user()->can('register_report.view')
            || auth()->user()->can('expense_report.view')
        ) {




            $menuItems[__('report.reports')] = [
                [
                    'name' => __('report.reports'),
                    'icon' => 'fa fas fa-chart-bar',
                    'id' => 'tour_step8',
                    'order' => 55,
                    'sub' => [
                        [
                            'name' => __('report.profit_loss'),
                            'url' => action('ReportController@getProfitLoss'),
                            'icon' => 'fa fas fa-file-invoice-dollar',
                            'active' => request()->segment(2) == 'profit-loss',
                            'can' => auth()->user()->can('profit_loss_report.view'),
                        ],
                        [
                            'name' => 'يومية المبيعات',
                            'url' => action('ReportController@getsells'),
                            'icon' => 'fa fas fa-arrow-circle-down',
                            'active' => request()->segment(2) == 'getsells',
                        ],
                        [
                            'name' => 'Report 606 (' . __('lang_v1.purchase') . ')',
                            'url' => action('ReportController@purchaseReport'),
                            'icon' => 'fa fas fa-arrow-circle-down',
                            'active' => request()->segment(2) == 'purchase-report',
                            'can' => config('constants.show_report_606'),
                        ],
                        [
                            'name' => 'Report 607 (' . __('business.sale') . ')',
                            'url' => action('ReportController@saleReport'),
                            'icon' => 'fa fas fa-arrow-circle-up',
                            'active' => request()->segment(2) == 'sale-report',
                            'can' => config('constants.show_report_607'),
                        ],
                        [
                            'name' => __('report.purchase_sell_report'),
                            'url' => action('ReportController@getPurchaseSell'),
                            'icon' => 'fa fas fa-exchange-alt',
                            'active' => request()->segment(2) == 'purchase-sell',
                            'can' => (in_array('purchases', $enabled_modules) || in_array('add_sale', $enabled_modules) || in_array('pos_sale', $enabled_modules)) && auth()->user()->can('purchase_n_sell_report.view'),
                        ],
                        [
                            'name' => __('report.tax_report'),
                            'url' => action('ReportController@getTaxReport'),
                            'icon' => 'fa fas fa-percent',
                            'active' => request()->segment(2) == 'tax-report',
                            'can' => auth()->user()->can('tax_report.view'),
                        ],
                        [
                            'name' => __('report.contacts'),
                            'url' => action('ReportController@getCustomerSuppliers'),
                            'icon' => 'fa fas fa-address-book',
                            'active' => request()->segment(2) == 'customer-supplier',
                            'can' => auth()->user()->can('contacts_report.view'),
                        ],
                        [
                            'name' => __('lang_v1.customer_groups_report'),
                            'url' => action('ReportController@getCustomerGroup'),
                            'icon' => 'fa fas fa-users',
                            'active' => request()->segment(2) == 'customer-group',
                            'can' => auth()->user()->can('contacts_report.view'),
                        ],
                        [
                            'name' => __('report.stock_report'),
                            'url' => action('ReportController@getStockReport'),
                            'icon' => 'fa fas fa-hourglass-half',
                            'active' => request()->segment(2) == 'stock-report',
                            'can' => auth()->user()->can('stock_report.view'),
                        ],
                        [
                            'name' =>  __('report.stock_expiry_report'),
                            'url' => action('ReportController@getStockExpiryReport'),
                            'icon' => 'fa fas fa-calendar-times',
                            'active' => request()->segment(2) == 'stock-expiry',
                            'can' => session('business.enable_product_expiry') == 1 && auth()->user()->can('stock_report.view'),
                        ],
                        [
                            'name' => __('lang_v1.lot_report'),
                            'url' => action('ReportController@getLotReport'),
                            'icon' => 'fa fas fa-hourglass-half',
                            'active' => request()->segment(2) == 'lot-report',
                            'can' => session('business.enable_lot_number') == 1 && auth()->user()->can('stock_report.view'),
                        ],
                        [
                            'name' => __('report.stock_adjustment_report'),
                            'url' => action('ReportController@getStockAdjustmentReport'),
                            'icon' => 'fa fas fa-sliders-h',
                            'active' => request()->segment(2) == 'stock-adjustment-report',
                            'can' => in_array('stock_adjustment', $enabled_modules) && auth()->user()->can('stock_report.view'),
                        ],
                        [
                            'name' => __('report.trending_products'),
                            'url' => action('ReportController@getTrendingProducts'),
                            'icon' => 'fa fas fa-chart-line',
                            'active' => request()->segment(2) == 'trending-products',
                            'can' => auth()->user()->can('trending_product_report.view'),
                        ],
                        // ----------


                        [
                            'name' => __('lang_v1.items_report'),
                            'url' =>  action('ReportController@itemsReport'),
                            'icon' => 'fa fas fa-tasks',
                            'active' => request()->segment(2) == 'items-report',
                            'can' => auth()->user()->can('purchase_n_sell_report.view'),
                        ],
                        [
                            'name' => __('lang_v1.product_purchase_report'),
                            'url' => action('ReportController@getproductPurchaseReport'),
                            'icon' => 'fa fas fa-arrow-circle-down',
                            'active' => request()->segment(2) == 'product-purchase-report',
                            'can' => auth()->user()->can('purchase_n_sell_report.view'),
                        ],
                        [
                            'name' => __('lang_v1.product_sell_report'),
                            'url' => action('ReportController@getproductSellReport'),
                            'icon' => 'fa fas fa-arrow-circle-up',
                            'active' => request()->segment(2) == 'product-sell-report',
                            'can' => auth()->user()->can('purchase_n_sell_report.view'),
                        ],
                        [
                            'name' => __('lang_v1.purchase_payment_report'),
                            'url' => action('ReportController@purchasePaymentReport'),
                            'icon' => 'fa fas fa-search-dollar',
                            'active' => request()->segment(2) == 'purchase-payment-report',
                            'can' => auth()->user()->can('purchase_n_sell_report.view'),
                        ],
                        [
                            'name' => __('lang_v1.sellPaymentReport'),
                            'url' => action('ReportController@sellPaymentReport'),
                            'icon' => 'fa fas fa-search-dollar',
                            'active' => request()->segment(2) == 'sell-payment-report',
                            'can' => auth()->user()->can('purchase_n_sell_report.view'),
                        ],
                        [
                            'name' => __('report.expense_report'),
                            'url' => action('ReportController@getExpenseReport'),
                            'icon' => 'fa fas fa-search-minus',
                            'active' => request()->segment(2) == 'expense-report',
                            'can' => in_array('expenses', $enabled_modules) && auth()->user()->can('expense_report.view'),
                        ],
                        // -----------

                        [
                            'name' => __('report.register_report'),
                            'url' => action('ReportController@getRegisterReport'),
                            'icon' => 'fa fas fa-briefcase',
                            'active' => request()->segment(2) == 'register-report',
                            'can' => auth()->user()->can('register_report.view'),
                        ],
                        [
                            'name' => __('report.sales_representative'),
                            'url' => action('ReportController@getSalesRepresentativeReport'),
                            'icon' => 'fa fas fa-user',
                            'active' => request()->segment(2) == 'sales-representative-report',
                            'can' => auth()->user()->can('sales_representative.view'),
                        ],
                        [
                            'name' => __('lang_v1.activity_log'),
                            'url' => action('ReportController@activityLog'),
                            'icon' => 'fa fas fa-user-secret',
                            'active' => request()->segment(2) == 'activity-log',
                            'can' => $is_admin,
                        ],
                    ],
                ]
            ];
        }



        if (auth()->user()->can('backup')) {

            $menuItems[__('lang_v1.backup')] = [
                [
                    'name' => __('lang_v1.backup'),
                    'url' => action('BackUpController@index'),
                    'icon' => 'fa fas fa-hdd',
                    'active' => request()->segment(1) == 'backup',
                    'order' => 60,
                ]
            ];
        }


        if (auth()->user()->can('manage_modules')) {

            $menuItems[__('lang_v1.modules')] = [
                [
                    'name' => __('lang_v1.modules'),
                    'url' => action('Install\ModulesController@index'),
                    'icon' => 'fa fas fa-plug',
                    'active' => request()->segment(1) == 'manage-modules',
                    'order' => 61,
                ]
            ];
        }





        $menuItems[__('payment.payments')] = [
            [
                'name' => __('payment.payments'),
                'icon' => 'fa fas fa-chart-bar',
                'id' => 'tour_step8',
                'order' => 8,
                'sub' => [
                    [
                        'name' => __('lang_v1.sales_receipts'),
                        'url' => action('PaymentController@index', ['type' => 'sales']),
                        'icon' => 'fa fas fa-file-import',
                        'active' => request()->segment(1) == 'import-sales',
                    ],
                    [
                        'name' => __('lang_v1.purchases_receipts'),
                        'url' => action('PaymentController@index', ['type' => 'purchases']),
                        'icon' => 'fa fas fa-file-import',
                        'active' => request()->segment(1) == 'import-purchases',
                    ],
                ],
            ]
        ];



        if (auth()->user()->can('send_notifications')) {

            $menuItems[__('lang_v1.notification_templates')] = [
                [
                    'name' => __('lang_v1.notification_templates'),
                    'url' => action('NotificationTemplateController@index'),
                    'icon' => 'fa fas fa-envelope',
                    'active' => request()->segment(1) == 'notification-templates',
                    'order' => 80,
                ]
            ];
        }

        if (
            auth()->user()->can('business_settings.access') ||
            auth()->user()->can('barcode_settings.access') ||
            auth()->user()->can('invoice_settings.access') ||
            auth()->user()->can('tax_rate.view') ||
            auth()->user()->can('tax_rate.create') ||
            auth()->user()->can('access_package_subscriptions')
        ) {

            $menuItems[__('business.settings')] = [
                [
                    'name' => __('business.settings'),
                    'icon' => 'fa fas fa-cog',
                    'id' => 'tour_step3',
                    'order' => 85,
                    'sub' => [
                        [
                            'name' => __('business.business_settings'),
                            'url' => action('BusinessController@getBusinessSettings'),
                            'icon' => 'fa fas fa-cogs',
                            'active' => request()->segment(1) == 'business',
                            'id' => 'tour_step2',
                            'can' => auth()->user()->can('business_settings.access'),
                        ],
                        [
                            'name' => __('business.business_locations'),
                            'url' => action('BusinessLocationController@index'),
                            'icon' => 'fa fas fa-map-marker',
                            'active' => request()->segment(1) == 'business-location',
                            'can' => auth()->user()->can('business_settings.access'),
                        ],
                        [
                            'name' => __('invoice.invoice_settings'),
                            'url' => action('InvoiceSchemeController@index'),
                            'icon' => 'fa fas fa-file',
                            'active' => in_array(request()->segment(1), ['invoice-schemes', 'invoice-layouts']),
                            'can' => auth()->user()->can('invoice_settings.access'),
                        ],
                        [
                            'name' => __('barcode.barcode_settings'),
                            'url' => action('BarcodeController@index'),
                            'icon' => 'fa fas fa-barcode',
                            'active' => request()->segment(1) == 'barcodes',
                            'can' => auth()->user()->can('barcode_settings.access'),
                        ],
                        [
                            'name' => __('printer.receipt_printers'),
                            'url' => action('PrinterController@index'),
                            'icon' => 'fa fas fa-share-alt',
                            'active' => request()->segment(1) == 'printers',
                            'can' => auth()->user()->can('access_printers'),
                        ],
                        [
                            'name' => __('tax_rate.tax_rates'),
                            'url' => action('TaxRateController@index'),
                            'icon' => 'fa fas fa-bolt',
                            'active' => request()->segment(1) == 'tax-rates',
                            'can' => auth()->user()->can('tax_rate.view') || auth()->user()->can('tax_rate.create'),
                        ],
                        [
                            'name' => __('lang_v1.types_of_service'),
                            'url' => action('TypesOfServiceController@index'),
                            'icon' => 'fa fas fa-user-circle',
                            'active' => request()->segment(1) == 'types-of-service',
                            'can' => in_array('types_of_service', $enabled_modules) && auth()->user()->can('access_types_of_service'),


                        ],
                    ],
                ]
            ];
        }

  
        // $request['menuItems'] = $menuItems;

        $modifyAdminMenu = [];
        //Add menus from modules
        $moduleUtil = new ModuleUtil;
        $menuItems = $moduleUtil->getModuleData('modifyAdminMenu', $menuItems);

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
        // dd($kk);
        // dd($menuItems);
        // $request['menuItems'] = array_merge($request['menuItems'], $menuItems);
        $request['menuItems'] = $modifyAdminMenu;

        return $next($request);
    }
}
