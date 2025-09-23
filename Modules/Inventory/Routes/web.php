<?php

use Illuminate\Support\Facades\Route;
// use Modules\Inventory\Http\Controllers\InstallController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



// Route::middleware(['web', 'SetSessionData', 'auth', 'language', 'timezone', 'AdminSidebarMenu'])
//     ->prefix('inventory')
//     ->group(function () {

//         Route::prefix('/install')->controller('InstallController')->group(function () {
//             Route::get('/', 'index');
//             Route::post('/', 'install');
//             Route::get('/uninstall', 'uninstall');
//             Route::get('/update', 'update');
//         });


//         Route::prefix('inventory')->as('inventory.')->controller('InventoryController')->group(function () {

//             Route::get('/', 'index')->name('index');
//             Route::get('/create', 'create')->name('create');
//             Route::post('/', 'store')->name('store');
//             Route::get('/{id}', 'show')->name('show');
//             Route::get('/{id}/edit', 'edit')->name('edit');
//             // Route::put('/inventory/{id}', 'update')->name('update');
//             // Route::delete('/inventory/{id}', 'destroy')->name('destroy');

//             Route::get('/stock/stocking/', 'stocking');
//             Route::get('/stock/stock_line_save/', 'stock_line_save');
//             Route::get('/stock/delete_stock', 'delete_stock');
//             Route::get('/stock/changestatus', 'changestatus');
//             Route::get('/stock/get_last_product', 'get_last_product');
//             Route::get('/stock/getproduct', 'getproduct');

//             Route::post('/stock/savestocking', 'savestocking');

//             Route::get('/stock/deletstock', 'deletstock');
//         });

//         Route::prefix('stock')->controller('StocktackingController')->group(function () {
//             Route::get('/report', 'report');
//             Route::get('/details_report', 'details_report');

//             Route::get('/report_plus', 'report_plus');
//             Route::get('/report_minus', 'report_minus');
//         });


//     });







Route::middleware([
    'web',
    'SetSessionData',
    'auth',
    'language',
    'timezone',
    'AdminSidebarMenu'
])
    ->prefix('inventory')
    ->group(function () {

        Route::prefix('/install')->controller('InstallController')->group(function () {
            Route::get('/', 'index');
            Route::post('/', 'install');
            Route::get('/uninstall', 'uninstall');
            Route::get('/update', 'update');
        });


        Route::prefix('inventory')->as('inventory.')->controller('InventoryController')->group(function () {

            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{id}', 'show')->name('show');
            Route::get('/{id}/edit', 'edit')->name('edit');
            // Route::put('/inventory/{id}', 'update')->name('update');
            // Route::delete('/inventory/{id}', 'destroy')->name('destroy');

            Route::get('/stock/stocking/', 'stocking');
            Route::get('/stock/stock_line_save/', 'stock_line_save');
            Route::get('/stock/delete_stock', 'delete_stock');
            Route::get('/stock/changestatus', 'changestatus');
            Route::get('/stock/get_last_product', 'get_last_product');
            Route::get('/stock/getproduct', 'getproduct');

            Route::post('/stock/savestocking', 'savestocking');

            Route::get('/stock/deletstock', 'deletstock');
        });

        Route::prefix('stock')->controller('StocktackingController')->group(function () {
            Route::get('/report', 'report');
            Route::get('/details_report', 'details_report');

            Route::get('/report_plus', 'report_plus');
            Route::get('/report_minus', 'report_minus');
        });
    });
