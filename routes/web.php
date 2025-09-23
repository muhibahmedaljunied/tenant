<?php

use App\Http\Controllers\ItGuy\TenantController;
use App\Http\Controllers\ItGuy\UserController;
use Illuminate\Support\Facades\{Auth, DB, Log, Route};
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

Log::info("message_in_route web.php" . DB::connection()->getDatabaseName());
// Auth::check();
// Log::info("message_in_route of web.php" . DB::connection()->getDatabaseName());

Auth::routes();
Route::get('/check', function () {
    return 'Tenant ID: central';
});
// Route::get('/home22', 'HomeController@index')->name('home');

Route::middleware([
    'auth',
    'setData',
    'ITGuySetSessionData',
    'language',
    // 'timezone',
    'ITGuy',
])->group(function () {


    Route::get('/', 'ItGuy\HomeController@index');
    Route::get('/home', 'ItGuy\HomeController@index')->name('home');
    Route::get('tenants/index', 'ItGuy\TenantController@index');
    Route::get('tenants/create', 'ItGuy\TenantController@create');
    Route::post('tenants/store', 'ItGuy\TenantController@store');

    Route::get('tenants/{tenant}/edit', [TenantController::class, 'edit'])->name('tenants.edit');
    Route::post('tenants/{tenant}/update', [TenantController::class, 'update'])->name('tenants.update');

    // Route::get('tenants/edit/{id}', [TenantController::class, 'edit'])->name('tenants.edit');
    Route::delete('tenants/{id}', [TenantController::class, 'destroy'])->name('tenants.destroy');




    // Route::get('tenants/edit/{id}', 'ItGuy\TenantController@edit');
    // Route::post('tenants/distroy', 'ItGuy\TenantController@destroy');
    Route::get('users/index', 'ItGuy\UserController@index');
    Route::get('users/create', 'ItGuy\UserController@create');
    Route::get('users/store', 'ItGuy\UserController@store');


    Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::post('users/{user}/update', [UserController::class, 'update'])->name('users.update');
    Route::post('users/{user}/delete', [UserController::class, 'destroy'])->name('users.destroy');



    // -----------------------------------------------------------------------
    Route::resource('pos', 'SellPosController');

    // Route::resource('roles', 'RoleController');

    // Route::resource('users', 'ManageUserController');

    Route::resource('group-taxes', 'GroupTaxController');


    Route::resource('sales-commission-agents', 'SalesCommissionAgentController');
});


// Route::middleware(['auth'])->group(function () {
//     Route::get('/logout', \App\Http\Controllers\Auth\CustomLogoutController::class)->name('logout');
// });



Route::middleware(['auth'])->group(function () {
    Route::get('/logout', 'Auth\LoginController@logout')->name('logout');
});
