<?php

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

use Modules\Installment\Http\Controllers\CustomerController;
use Modules\Installment\Http\Controllers\InstallController;
use Modules\Installment\Http\Controllers\InstallmentController;
use Modules\Installment\Http\Controllers\InstallmentSystemController;
use Modules\Installment\Http\Controllers\SellController;

Route::middleware([
    'web',
    'SetSessionData',
    'auth',
    'language',
    'timezone',
    'AdminSidebarMenu'
])->prefix('installment')->group(function () {
    Route::get('/', [InstallmentController::class,'index']);
    Route::get('/install',  [InstallController::class,'index']);
    Route::post('/install', [InstallController::class,'install']);
    Route::get('/install/uninstall',  [InstallController::class,'uninstall']);
    Route::get('/install/update',  [InstallController::class,'update']);


    Route::resource('/installment', 'InstallmentController');
    Route::get('/installments',[InstallmentController::class,'instalments']);
    Route::get('/installmentdelete/{id}', [InstallmentController::class,'installmentdelete']);
    Route::get('/paymentdelete/{id}',[InstallmentController::class,'paymentdelete']);
    Route::get('/addpayment/{id}', [InstallmentController::class,'addpayment']);
    Route::post('/storepayment', [InstallmentController::class,'storepayment']);
    Route::get('/business', [InstallmentController::class,'business']);
 
    Route::get('/printinstallment/{id}', [InstallmentController::class,'printinstallment']);

    Route::resource('/system','InstallmentSystemController');
    Route::get('/getsystemdata', [InstallmentSystemController::class,'getsystemdata']);


    Route::resource('/customer', 'CustomerController');
    Route::get('/getcustomerdata/{id}', [CustomerController::class,'getcustomerdata']);

    Route::get('/createinstallment2/{id}/{total}/{paid?}', [CustomerController::class,'createinstallment2']);
    Route::post('/createinstallment',[CustomerController::class,'createinstallment'] );
    Route::get('/getinstallment',[CustomerController::class,'getinstallment']);
    Route::get('/contacts',[CustomerController::class,'contacts']);
    Route::get('/contactwithinstallment', [CustomerController::class,'contactwithinstallment']);


    Route::get('/sells', [SellController::class,'index']);
});
