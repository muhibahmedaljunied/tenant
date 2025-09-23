<?php
// Route::get('/repair-status', 'Modules\Repair\Http\Controllers\CustomerRepairStatusController@index')->name('repair-status');
// Route::post('/post-repair-status', 'Modules\Repair\Http\Controllers\CustomerRepairStatusController@postRepairStatus')->name('post-repair-status');
// Route::group(['middleware' => [
//     'web',
//     'authh',
//     'auth',
//     'SetSessionData',
//     'language',
//     'timezone',
//     'AdminSidebarMenu'
// ], 'prefix' => 'repair', 'namespace' => 'Modules\Repair\Http\Controllers'], function () {
//     Route::get('edit-repair/{id}/status', 'RepairController@editRepairStatus');
//     Route::post('update-repair-status', 'RepairController@updateRepairStatus');
//     Route::get('delete-media/{id}', 'RepairController@deleteMedia');
//     Route::get('print-label/{id}', 'RepairController@printLabel');
//     Route::resource('/repair', 'RepairController')->except(['create', 'edit']);
//     Route::resource('/status', 'RepairStatusController', ['except' => ['show']]);
//     Route::resource('/guarantee', 'GuaranteeController');
//     Route::any('/guarantee/get_products_sold', 'GuaranteeController@get_products_sold');
//     Route::any('/guarantee/get_invoice_sold', 'GuaranteeController@get_invoice_sold');
//     Route::any('/guarantee/get_supplier', 'GuaranteeController@get_supplier');
//     Route::get('guarantee/{id}/status', 'GuaranteeController@editStatus');
//     Route::put('guarantee-update/{id}/status', 'GuaranteeController@updateStatus');
//     Route::get('guarantee/print_slim/{id}', 'GuaranteeController@print_slim');

//     Route::resource('/repair-settings', 'RepairSettingsController', ['only' => ['index', 'store']]);

//     Route::get('/install', 'InstallController@index');
//     Route::post('/install', 'InstallController@install');
//     Route::get('/install/uninstall', 'InstallController@uninstall');
//     Route::get('/install/update', 'InstallController@update');

//     Route::get('get-device-models', 'DeviceModelController@getDeviceModels');
//     Route::get('models-repair-checklist', 'DeviceModelController@getRepairChecklists');
//     Route::resource('device-models', 'DeviceModelController')->except(['show']);
//     Route::resource('dashboard', 'DashboardController');

//     Route::get('job-sheet/delete/{id}/image', 'JobSheetController@deleteJobSheetImage');
//     Route::get('job-sheet/{id}/status', 'JobSheetController@editStatus');
//     Route::put('job-sheet-update/{id}/status', 'JobSheetController@updateStatus');
//     Route::resource('job-sheet', 'JobSheetController');
//     Route::get('job-sheet/print_slim/{id}', 'JobSheetController@print_slim');
// });





use Modules\Repair\Http\Controllers\CustomerRepairStatusController;
use Modules\Repair\Http\Controllers\DashboardController;
use Modules\Repair\Http\Controllers\DeviceModelController;
use Modules\Repair\Http\Controllers\GuaranteeController;
use Modules\Repair\Http\Controllers\InstallController;
use Modules\Repair\Http\Controllers\JobSheetController;
use Modules\Repair\Http\Controllers\RepairController;
use Modules\Repair\Http\Controllers\RepairSettingsController;

Route::get('/repair-status', [CustomerRepairStatusController::class, 'index'])->name('repairStatus-index');
Route::get('/repair-status-create', [CustomerRepairStatusController::class, 'create'])->name('repairStatus-create');

Route::post('/post-repair-status', [CustomerRepairStatusController::class, 'postRepairStatus'])->name('post-repair-status');
Route::middleware([
    'web',
    'authh',
    'auth',
    'SetSessionData',
    'language',
    'timezone',
    'AdminSidebarMenu'
])->group(function () {
    Route::get('/repair/edit-repair/{id}/status', [RepairController::class, 'editRepairStatus']);
    Route::post('/repairupdate-repair-status', [RepairController::class, 'updateRepairStatus']);
    Route::get('/repairdelete-media/{id}', [RepairController::class, 'deleteMedia']);
    Route::get('/repairprint-label/{id}', [RepairController::class, 'printLabel']);

    // Route::resource('/repair/repair', [RepairController::class, 'index']'RepairController')->except(['create', 'edit']);
    // Route::resource('/repair/status', [RepairController::class, 'index']'RepairStatusController', ['except' => ['show']]);


    Route::get('/repair/repair-index', [RepairController::class, 'index'])->name('repair-index');



    // Route::resource('/repair/guarantee', 'GuaranteeController');


    Route::get('/repair/guarantee-index', [GuaranteeController::class, 'index'])->name('guarantee-index');
    Route::get('/repair/guarantee-create', [GuaranteeController::class, 'create'])->name('guarantee-create');

    Route::any('/repair/guarantee/get_products_sold', [GuaranteeController::class, 'get_products_sold']);
    Route::any('/repair/guarantee/get_invoice_sold', [GuaranteeController::class, 'get_invoice_sold']);
    Route::any('/repair/guarantee/get_supplier', [GuaranteeController::class, 'get_supplier']);
    Route::get('/repairguarantee/{id}/status', [GuaranteeController::class, 'editStatus']);
    Route::put('/repairguarantee-update/{id}/status', [GuaranteeController::class, 'updateStatus']);
    Route::get('/repairguarantee/print_slim/{id}', [GuaranteeController::class, 'print_slim']);

    // Route::resource('/repair-settings', 'RepairSettingsController', ['only' => ['index', 'store']]);
    Route::get('/repair-settings', [RepairSettingsController::class, 'index'])->name('repairSettings-index');
    Route::post('/repair-settings-store', [RepairSettingsController::class, 'store'])->name('repairSettings-store');

    Route::get('/repair/install', [InstallController::class, 'index']);
    Route::post('/repair/install', [InstallController::class, 'install']);
    Route::get('/repair/install/uninstall', [InstallController::class, 'uninstall']);
    Route::get('/repair/install/update', [InstallController::class, 'update']);

    Route::get('/repair/get-device-models', [DeviceModelController::class, 'getDeviceModels']);
    Route::get('/repair/models-repair-checklist', [DeviceModelController::class, 'getRepairChecklists']);

    Route::resource('/repair/device-models', 'DeviceModelController')->except(['show']);
    Route::get('/repair/device-models',[DeviceModelController::class, 'create'])->name('deviceModel-create');

    // Route::resource('/repair/dashboard', 'DashboardController');

    Route::get('/repair/dashboard', [DashboardController::class, 'index'])->name('dashboard-index');

    Route::get('/repair/job-sheet/delete/{id}/image', [JobSheetController::class, 'deleteJobSheetImage']);
    Route::get('/repair/job-sheet/{id}/status', [JobSheetController::class, 'editStatus']);
    Route::put('/repair/job-sheet-update/{id}/status', [JobSheetController::class, 'updateStatus']);

    // Route::resource('/repair/job-sheet', 'JobSheetController');

    Route::get('/repair/job-sheet-index', [JobSheetController::class, 'index'])->name('jobSheet-index');
    Route::get('/repair/job-sheet-create', [JobSheetController::class, 'create'])->name('jobSheet-create');
    Route::post('/repair/job-sheet-store', [JobSheetController::class, 'store'])->name('jobSheet-store');



    Route::get('/repair/job-sheet/print_slim/{id}',  [JobSheetController::class, 'print_slim']);
});
