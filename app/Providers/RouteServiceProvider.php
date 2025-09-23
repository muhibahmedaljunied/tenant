<?php

namespace App\Providers;

use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapTenantRoutes();

        $this->mapApiRoutes();

        // $this->mapWebRoutes();
        $this->mapWebsiteRoutes();

        $this->loadRoutesFrom(base_path('routes/console.php'));
    }


    public function mapTenantRoutes()
    {

        $tenantRouteFiles = [
            base_path('routes/tenant.php'),
            // base_path('Modules/Assets/Http/routes.php'),
            // base_path('Modules/ChartOfAccounts/Http/routes.php'),
            // base_path('Modules/Crm/Http/routes.php'),
            base_path('Modules/Essentials/Http/routes.php'),
            // base_path('Modules/GeneralAccount/Http/routes.php'),
            base_path('Modules/Installment/Http/routes.php'),
            // base_path('Modules/Inventory/Http/routes.php'),
            base_path('Modules/Manufacturing/Http/routes.php'),
            // base_path('Modules/Partners/Http/routes.php'),
            base_path('Modules/ProductCatalogue/Http/routes.php'),
            base_path('Modules/Project/Http/routes.php'),
            base_path('Modules/Repair/Http/routes.php'),
            // base_path('Modules/Restaurant/Http/routes.php'),
            base_path('Modules/Superadmin/Http/routes.php'),
            // base_path('Modules/Tracker/Http/routes.php'),
            // base_path('Modules/Web/Http/routes.php'),
            base_path('Modules/Woocommerce/Http/routes.php'),
            // glob(base_path('Modules/*/Http/routes.php'))
        ];


        if (in_array(request()->getHost(), config('tenancy.central_domains'))) {

            Route::middleware('web')
                ->domain(request()->getHost())
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));
        } else {


            Route::middleware([
                'web',
                InitializeTenancyByDomain::class,
                PreventAccessFromCentralDomains::class,

            ])

                ->namespace($this->namespace)
                ->group(
                    function () use ($tenantRouteFiles) {
                        foreach ($tenantRouteFiles as $file) {
                            if (file_exists($file)) {
                                require  $file;
                            }
                        }
                    }




                );
        }


    }
    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }


    protected function mapWebsiteRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/website.php'));
    }
    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
    }
}
