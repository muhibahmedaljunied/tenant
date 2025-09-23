<?php
// use Modules\Superadmin\Http\Controllers\PricingController;
// use Modules\Superadmin\Http\Controllers\SubscriptionController;

// Route::get('/pricing', [PricingController::class, 'index'])->name('pricing');

// Route::group(
//     [
//         'middleware' => [
//             'web',
//             'auth',
//             'language',
//             'AdminSidebarMenu',
//             'superadmin'
//         ],
//         'prefix' => 'superadmin',
//         'namespace' => 'Modules\Superadmin\Http\Controllers'
//     ],
//     function () {
//         // Route::get('/pricing','PricingController@index')->name('pricing');
//         // Route::get('/pricing', [PricingController::class, 'index'])->name('pricing');
    
//         Route::get('/install', 'InstallController@index');
//         Route::get('/install/update', 'InstallController@update');
//         Route::get('/install/uninstall', 'InstallController@uninstall');

//         Route::get('/', 'SuperadminController@index');
//         Route::get('/stats', 'SuperadminController@stats');

//         Route::get('/{business_id}/toggle-active/{is_active}', 'BusinessController@toggleActive');

//         Route::get('/users/{business_id}', 'BusinessController@usersList');
//         Route::post('/update-password', 'BusinessController@updatePassword');


//         Route::resource('/business', 'BusinessController');
//         Route::get('/business/{id}/destroy', 'BusinessController@destroy');

//         Route::resource('/packages', 'PackagesController');
//         Route::get('/packages/{id}/destroy', 'PackagesController@destroy');

//         Route::get('/settings', 'SuperadminSettingsController@edit');
//         Route::put('/settings', 'SuperadminSettingsController@update');
//         Route::get('/edit-subscription/{id}', 'SuperadminSubscriptionsController@editSubscription');
//         Route::post('/update-subscription', 'SuperadminSubscriptionsController@updateSubscription');
//         Route::resource('/superadmin-subscription', 'SuperadminSubscriptionsController');

//         Route::get('/communicator', 'CommunicatorController@index');
//         Route::post('/communicator/send', 'CommunicatorController@send');
//         Route::get('/communicator/get-history', 'CommunicatorController@getHistory');

//         Route::resource('/frontend-pages', 'PageController');
//         Route::get('/change-business', 'SuperadminController@change_business');
//     }
// );

// Route::group([
//     'middleware' => [
//         'web',
//         'SetSessionData',
//         'auth',
//         'language',
//         'timezone',
//         'AdminSidebarMenu',
        
//     ],
//     // 'namespace' => 'Modules\Superadmin\Http\Controllers'

// ], function () {

//     // Route::get('/pricing','PricingController@index')->name('pricing');

//     //Routes related to paypal checkout
//     Route::get('/subscription/{package_id}/paypal-express-checkout', 'SubscriptionController@paypalExpressCheckout');
//     // Route::get('/muhibsad', 'SubscriptionController@paypalExpressCheckout');
//     //Routes related to pesapal checkout
//     Route::get('/subscription/{package_id}/pesapal-callback', ['as' => 'pesapalCallback', 'uses' => 'SubscriptionController@pesapalCallback']);

//     Route::get('/subscription/{package_id}/pay', 'SubscriptionController@pay');
//     Route::any('/subscription/{package_id}/confirm', 'SubscriptionController@confirm')->name('subscription-confirm');
//     Route::get('/all-subscriptions', 'SubscriptionController@allSubscriptions');

//     Route::get('/subscription/{package_id}/register-pay', 'SubscriptionController@registerPay')->name('register-pay');

//     // Route::resource('/subscription', 'Modules/Superadmin/Http/Controllers/SubscriptionController');
//     Route::resource('/subscription', 'SubscriptionController');

// });

// Route::get('/page/{slug}', 'Modules\Superadmin\Http\Controllers\PageController@showPage')->name('frontend-pages');




// -----------------------------------------------
use Modules\Superadmin\Http\Controllers\PricingController;
use Modules\Superadmin\Http\Controllers\SubscriptionController;
use Modules\Superadmin\Http\Controllers\InstallController;
use Modules\Superadmin\Http\Controllers\SuperadminController;
use Modules\Superadmin\Http\Controllers\BusinessController;
use Modules\Superadmin\Http\Controllers\PackagesController;
use Modules\Superadmin\Http\Controllers\SuperadminSettingsController;
use Modules\Superadmin\Http\Controllers\SuperadminSubscriptionsController;
use Modules\Superadmin\Http\Controllers\CommunicatorController;
use Modules\Superadmin\Http\Controllers\PageController;

Route::get('/pricing', [PricingController::class, 'index'])->name('pricing');

Route::prefix('superadmin')->middleware([
    'auth',
    'language',
    'AdminSidebarMenu',
    'superadmin'
])->group(function () {
    Route::get('/install', [InstallController::class, 'index']);
    Route::get('/install/update', [InstallController::class, 'update']);
    Route::get('/install/uninstall', [InstallController::class, 'uninstall']);

    Route::get('/', [SuperadminController::class, 'index']);
    Route::get('/stats', [SuperadminController::class, 'stats']);

    Route::get('/{business_id}/toggle-active/{is_active}', [BusinessController::class, 'toggleActive']);
    Route::get('/users/{business_id}', [BusinessController::class, 'usersList']);
    Route::post('/update-password', [BusinessController::class, 'updatePassword']);

    Route::resource('/business', BusinessController::class);
    Route::get('/business/{id}/destroy', [BusinessController::class, 'destroy']);

    Route::resource('/packages', PackagesController::class);
    Route::get('/packages/{id}/destroy', [PackagesController::class, 'destroy']);

    Route::get('/settings', [SuperadminSettingsController::class, 'edit']);
    Route::put('/settings', [SuperadminSettingsController::class, 'update']);
    Route::get('/edit-subscription/{id}', [SuperadminSubscriptionsController::class, 'editSubscription']);
    Route::post('/update-subscription', [SuperadminSubscriptionsController::class, 'updateSubscription']);
    Route::resource('/superadmin-subscription', SuperadminSubscriptionsController::class);

    Route::get('/communicator', [CommunicatorController::class, 'index']);
    Route::post('/communicator/send', [CommunicatorController::class, 'send']);
    Route::get('/communicator/get-history', [CommunicatorController::class, 'getHistory']);

    Route::resource('/frontend-pages', PageController::class);
    Route::get('/change-business', [SuperadminController::class, 'change_business']);
});

Route::middleware([
    'SetSessionData',
    'auth',
    'language',
    'timezone',
    'AdminSidebarMenu'
])->group(function () {
    Route::get('/subscription/{package_id}/paypal-express-checkout', [SubscriptionController::class, 'paypalExpressCheckout']);
    Route::get('/subscription/{package_id}/pesapal-callback', [SubscriptionController::class, 'pesapalCallback'])->name('pesapalCallback');
    Route::get('/subscription/{package_id}/pay', [SubscriptionController::class, 'pay'])->name('subscription-pay');
    Route::any('/subscription/{package_id}/confirm', [SubscriptionController::class, 'confirm'])->name('subscription-confirm');
    Route::get('/all-subscriptions', [SubscriptionController::class, 'allSubscriptions']);
    Route::get('/subscription/{package_id}/register-pay', [SubscriptionController::class, 'registerPay'])->name('register-pay');
    // Route::resource('/subscription', SubscriptionController::class);
    Route::get('/subscription-index', [SubscriptionController::class,'index'])->name('subscription-index');
    Route::get('/subscription-all', [SubscriptionController::class,'allSubscriptions'])->name('subscription-all');
    Route::get('/subscription-show/{package_id}', [SubscriptionController::class,'show'])->name('subscription-show');
    Route::get('/subscription-confirm', [SubscriptionController::class,'confirm'])->name('subscription-confirm');
    // Route::get('/subscription-pay', [SubscriptionController::class,'pay'])->name('subscription-pay');
    // Route::get('/subscription', [SubscriptionController::class,'paypalExpressCheckout'])->name('subscription-paypalExpressCheckout');

    
});

Route::get('/page/{slug}', [PageController::class, 'showPage'])->name('frontend-pages');

