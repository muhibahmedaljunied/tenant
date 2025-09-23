<?php

use Illuminate\Support\Facades\Route;
use Modules\Woocommerce\Http\Controllers\InstallController;
use Modules\Woocommerce\Http\Controllers\WoocommerceController;
use Modules\Woocommerce\Http\Controllers\WoocommerceWebhookController;


Route::post(
    '/webhook/order-created/{business_id}',
    [WoocommerceWebhookController::class, 'orderCreated']
)->name('woocommerceWebhook-orderCreated');
Route::post(
    '/webhook/order-updated/{business_id}',
    [WoocommerceWebhookController::class, 'orderUpdated']
)->name('woocommerceWebhook-orderUpdated');
Route::post(
    '/webhook/order-deleted/{business_id}',
    [WoocommerceWebhookController::class, 'orderDeleted']
)->name('woocommerceWebhook-orderDeleted');
Route::post(
    '/webhook/order-restored/{business_id}',
    [WoocommerceWebhookController::class, 'orderRestored']
)->name('woocommerceWebhook-orderRestored');


Route::middleware([
    'web',
    'SetSessionData',
    'auth',
    'language',
    'timezone',
    'AdminSidebarMenu'
])->group(function () {


    Route::group(['prefix' => 'woocommerce'], function () {


        Route::get('/', [WoocommerceController::class, 'index'])->name('woocommerce-index');
        Route::get('/install', [InstallController::class, 'index']);
        Route::get('/install/update', [InstallController::class, 'update']);
        Route::get('/install/uninstall', [InstallController::class, 'uninstall']);
        Route::get('/api-settings', [WoocommerceController::class, 'apiSettings'])->name('woocommerce-apiSettings');
        Route::post('/update-api-settings', [WoocommerceController::class, 'updateSettings'])->name('woocommerce-updateSettings');
        Route::get('/sync-categories', [WoocommerceController::class, 'syncCategories'])->name('woocommerce-syncCategories');
        Route::get('/sync-products', [WoocommerceController::class, 'syncProducts'])->name('woocommerce-syncProducts');
        Route::get('/sync-log', [WoocommerceController::class, 'getSyncLog'])->name('woocommerce-getSyncLog');
        Route::get('/sync-orders', [WoocommerceController::class, 'syncOrders'])->name('woocommerce-syncOrders');
        Route::post('/map-taxrates', [WoocommerceController::class, 'mapTaxRates'])->name('woocommerce-mapTaxRates');
        Route::get('/view-sync-log', [WoocommerceController::class, 'viewSyncLog'])->name('woocommerce-viewSyncLog');
        Route::get('/get-log-details/{id}', [WoocommerceController::class, 'getLogDetails'])->name('woocommerce-getLogDetails');
        Route::get('/reset-categories', [WoocommerceController::class, 'resetCategories'])->name('woocommerce-resetCategories');
        Route::get('/reset-products', [WoocommerceController::class, 'resetProducts'])->name('woocommerce-resetProducts');
    });
});
