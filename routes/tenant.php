<?php

declare(strict_types=1);

use App\Http\Controllers\AcMasterController;
use Illuminate\Support\Facades\{Auth, DB, Log, Route};
use App\Http\Controllers\AcMaster\ImportAcMasterController;
use Modules\Superadmin\Http\Controllers\SubscriptionController;



/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Log::info("message_in_route tenant.php" . DB::connection()->getDatabaseName());

// include_once base_path('Modules/Superadmin/Http/routes.php');

// dd(base_path('Modules\Superadmin\Http\routes.php'));

include_once('install_r.php');

Route::get('/qrcode', 'GenQrcode@index');
Route::post('/qrcode', 'GenQrcode@test');



// ------------------------------------------------------


// tenancy()->routeGroup(function () {
//     require base_path('Modules/Superadmin/Routes/web.php');
// });

// ------------------------------------------------------

// Route::middleware([
//     'SetSessionData',
//     'auth',
//     'language',
//     'timezone',
//     'AdminSidebarMenu'
// ])->group(function () {

//     Route::get('/subscription/{package_id}/paypal-express-checkout', [SubscriptionController::class, 'paypalExpressCheckout']);
//     Route::get('/subscription/{package_id}/pesapal-callback', [SubscriptionController::class, 'pesapalCallback'])->name('pesapalCallback');
//     Route::get('/subscription/{package_id}/pay', [SubscriptionController::class, 'pay']);
//     Route::any('/subscription/{package_id}/confirm', [SubscriptionController::class, 'confirm'])->name('subscription-confirm');
//     Route::get('/all-subscriptions', [SubscriptionController::class, 'allSubscriptions']);
//     Route::get('/subscription/{package_id}/register-pay', [SubscriptionController::class, 'registerPay'])->name('register-pay');
//     Route::get('/subscription', [SubscriptionController::class,'index'])->name('subscription-index');
    
// });



Route::middleware(['setData'])->group(function () {

    Auth::routes();

    Route::get('/business/register', 'BusinessController@getRegister')->name('business.getRegister');
    Route::post('/business/register', 'BusinessController@postRegister')->name('business.postRegister');
    Route::post('/business/register/check-username', 'BusinessController@postCheckUsername')->name('business.postCheckUsername');
    Route::post('/business/register/check-email', 'BusinessController@postCheckEmail')->name('business.postCheckEmail');
    Route::get('/invoice/{token}', 'SellPosController@showInvoice')->name('show_invoice');
    Route::get('/quote/{token}', 'SellPosController@showInvoice')->name('show_quote');
});
// Route::get('/subscription',[SubscriptionController::class, 'index']);
//Routes for authenticated users only
Route::middleware([

    'setData',
    'auth',
    'SetSessionData',
    'language',
    'timezone',
    'AdminSidebarMenu',
    'CheckUserLogin'

 
])->group(function () {


    // 'namespace' => 'Modules\Superadmin\Http\Controllers'
    // Route::get('/subscription',[SubscriptionController::class, 'index']);


    Route::get('/', 'HomeController@index');
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/home/get-totals', 'HomeController@getTotals');
    Route::get('/home/product-stock-alert', 'HomeController@getProductStockAlert');
    Route::get('/home/purchase-payment-dues', 'HomeController@getPurchasePaymentDues');
    Route::get('/home/sales-payment-dues', 'HomeController@getSalesPaymentDues');
    Route::post('/attach-medias-to-model', 'HomeController@attachMediasToGivenModel')->name('attach.medias.to.model');
    Route::get('/calendar', 'HomeController@getCalendar')->name('calendar');
    Route::get('/depreciations', 'DepreciationController@index');
    Route::get('/depreciations/create', 'DepreciationController@create');
    Route::get('/depreciations/{depreciation}', 'DepreciationController@show')->name('depreciations.show');
    Route::delete('/depreciations/{depreciation}', 'DepreciationController@destroy');
    Route::post('/depreciations', 'DepreciationController@store');


    Route::post('/test-email', 'BusinessController@testEmailConfiguration');
    Route::post('/test-sms', 'BusinessController@testSmsConfiguration');
    Route::get('/business/settings', 'BusinessController@getBusinessSettings')->name('business.getBusinessSettings');
    Route::post('/business/update', 'BusinessController@postBusinessSettings')->name('business.postBusinessSettings');
    Route::put('/business/update_zatca', 'BusinessController@updateZatcaSetting')->name('business.update_zatca_setting');
    Route::get('/user/profile', 'UserController@getProfile')->name('user.getProfile');
    Route::post('/user/update', 'UserController@updateProfile')->name('user.updateProfile');
    Route::post('/user/update-password', 'UserController@updatePassword')->name('user.updatePassword');

    Route::resource('brands', 'BrandController');

    /*Route::resource('payment-account', 'PaymentAccountController');*/
    Route::prefix('zatca')->namespace('Zatca')->name('zatca.')->group(function () {
        Route::controller('ZatcaSettingsController')->prefix('settings')->name('settings.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('create', 'create')->name('create');
            Route::post('store', 'store')->name('store');
            Route::get('edit/{zatca}', 'edit')->name('edit');
            Route::get('show/{zatca}', 'show')->name('show');
            Route::put('update/{zatca}', 'update')->name('update');
            Route::delete('delete/{zatca}', 'destroy')->name('delete');
        });
        Route::controller('ZatcaInvoiceController')->prefix('invoices')->name('invoices.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('create', 'create')->name('create');
            Route::post('send', 'send')->name('send');
            Route::get('edit/{invoice}', 'edit')->name('edit');
            Route::get('show/{invoice}', 'show')->name('show');
            Route::put('update/{invoice}', 'update')->name('update');
            Route::delete('delete/{invoice}', 'destroy')->name('delete');
        });
    });
    Route::resource('tax-rates', 'TaxRateController');

    Route::resource('units', 'UnitController');

    // ------------------------------muhib add this----------------------

    Route::post('/get-stores-by-locations', 'StoreController@getStoresByLocations')->name('getStoresByLocationsStore');
    Route::resource('stores', 'StoreController');

    // ---------------------------------------------------------------------
    Route::get('/contacts/payments/{contact_id}', 'ContactController@getContactPayments');
    Route::get('/contacts/map', 'ContactController@contactMap');
    Route::get('/contacts/update-status/{id}', 'ContactController@updateStatus');
    Route::get('/contacts/stock-report/{supplier_id}', 'ContactController@getSupplierStockReport');
    Route::get('/contacts/ledger', 'ContactController@getLedger');
    Route::post('/contacts/send-ledger', 'ContactController@sendLedger');
    Route::get('/contacts/import', 'ContactController@getImportContacts')->name('contacts.import');
    Route::post('/contacts/import', 'ContactController@postImportContacts');
    Route::post('/contacts/check-contact-id', 'ContactController@checkContactId');
    Route::get('/contacts/customers', 'ContactController@getCustomers');
    Route::resource('contacts', 'ContactController');

    Route::get('taxonomies-ajax-index-page', 'TaxonomyController@getTaxonomyIndexPage');
    Route::resource('taxonomies', 'TaxonomyController');

    Route::resource('variation-templates', 'VariationTemplateController');

    Route::get('/products/stock-history/{id}', 'ProductController@productStockHistory');
    Route::get('/delete-media/{media_id}', 'ProductController@deleteMedia');
    Route::post('/products/mass-deactivate', 'ProductController@massDeactivate');
    Route::get('/products/activate/{id}', 'ProductController@activate');
    Route::get('/products/view-product-group-price/{id}', 'ProductController@viewGroupPrice');
    Route::get('/products/add-selling-prices/{id}', 'ProductController@addSellingPrices');
    Route::post('/products/save-selling-prices', 'ProductController@saveSellingPrices');
    Route::post('/products/mass-delete', 'ProductController@massDestroy');
    Route::get('/products/view/{id}', 'ProductController@view');

    Route::get('/products/addbarcode/{id}', 'ProductController@addbarcode');
    Route::get('/products/savebarcode', 'ProductController@savebarcode');
    Route::get('/products/getproductbarcode', 'ProductController@getproductbarcode');
    Route::get('/products/deletebarcode', 'ProductController@deletebarcode');

    Route::get('/products/list', 'ProductController@getProducts');
    Route::get('/products/list-no-variation', 'ProductController@getProductsWithoutVariations');
    Route::post('/products/bulk-edit', 'ProductController@bulkEdit');
    Route::post('/products/bulk-update', 'ProductController@bulkUpdate');
    Route::post('/products/bulk-update-location', 'ProductController@updateProductLocation');
    Route::get('/products/get-product-to-edit/{product_id}', 'ProductController@getProductToEdit');

    Route::post('/products/get_sub_categories', 'ProductController@getSubCategories');
    Route::get('/products/get_sub_units', 'ProductController@getSubUnits');
    Route::post('/products/product_form_part', 'ProductController@getProductVariationFormPart');
    Route::post('/products/get_product_variation_row', 'ProductController@getProductVariationRow');
    Route::post('/products/get_variation_template', 'ProductController@getVariationTemplate');
    Route::get('/products/get_variation_value_row', 'ProductController@getVariationValueRow');
    Route::post('/products/check_product_sku', 'ProductController@checkProductSku');
    Route::get('/products/quick_add', 'ProductController@quickAdd');
    Route::post('/products/save_quick_product', 'ProductController@saveQuickProduct');
    Route::get('/products/get-combo-product-entry-row', 'ProductController@getComboProductEntryRow');


    Route::post('/products/get-stores-by-locations', 'ProductController@getStoresByLocations')->name('getStoresByLocations');
    Route::resource('products', 'ProductController');


    //product Gallery



    Route::post('/purchases/update-status', 'PurchaseController@updateStatus');
    Route::get('/purchases/get_products', 'PurchaseController@getProducts');
    Route::get('/purchases/get_suppliers', 'PurchaseController@getSuppliers');
    Route::post('/purchases/get_purchase_entry_row', 'PurchaseController@getPurchaseEntryRow');
    Route::post('/purchases/check_ref_number', 'PurchaseController@checkRefNumber');
    Route::post('/purchases/get-stores-by-locations', 'PurchaseController@getStoresByLocations')->name('getStoresByLocationsPurchase');
    Route::resource('purchases', 'PurchaseController')->except(['show']);



    Route::get('/toggle-subscription/{id}', 'SellPosController@toggleRecurringInvoices');
    Route::post('/sells/pos/get-types-of-service-details', 'SellPosController@getTypesOfServiceDetails');
    Route::get('/sells/subscriptions', 'SellPosController@listSubscriptions');
    Route::get('/sells/duplicate/{id}', 'SellController@duplicateSell');
    Route::get('/sells/drafts', 'SellController@getDrafts');
    Route::get('/sells/convert-to-draft/{id}', 'SellPosController@convertToInvoice');
    Route::get('/sells/convert-to-proforma/{id}', 'SellPosController@convertToProforma');
    Route::get('/sells/quotations', 'SellController@getQuotations');
    Route::get('/sells/draft-dt', 'SellController@getDraftDatables');
    Route::post('/sell/get-stores-by-locations', 'SellController@getStoresByLocations')->name('getStoresByLocationsSell');
    Route::resource('sells', 'SellController')->except(['show']);

    Route::get('/sells/pos/search-for-transaction/{invoice_number}', 'SellPosController@getRecentTransactions');


    Route::get('/import-sales', 'ImportSalesController@index');
    Route::post('/import-sales/preview', 'ImportSalesController@preview');
    Route::post('/import-sales', 'ImportSalesController@import');
    Route::get('/revert-sale-import/{batch}', 'ImportSalesController@revertSaleImport');

    Route::get('/sells/pos/get_product_row/{variation_id}/{location_id}', 'SellPosController@getProductRow');
    Route::post('/sells/pos/get_payment_row', 'SellPosController@getPaymentRow');
    Route::post('/sells/pos/get-reward-details', 'SellPosController@getRewardDetails');
    Route::get('/sells/pos/get-recent-transactions', 'SellPosController@getRecentTransactions');
    Route::get('/sells/pos/get-product-suggestion', 'SellPosController@getProductSuggestion');
    Route::get('/sells/pos/get-featured-products/{location_id}', 'SellPosController@getFeaturedProducts');
    Route::post('/pos/get-stores-by-locations-', 'SellPosController@getStoresByLocations')->name('getStoresByLocationsPos');

    Route::resource('pos', 'SellPosController');

    Route::resource('roles', 'RoleController');

    Route::resource('users', 'ManageUserController');

    Route::resource('group-taxes', 'GroupTaxController');

    Route::get('/barcodes/set_default/{id}', 'BarcodeController@setDefault');
    Route::resource('barcodes', 'BarcodeController');

    //Invoice schemes..
    Route::get('/invoice-schemes/set_default/{id}', 'InvoiceSchemeController@setDefault');
    Route::resource('invoice-schemes', 'InvoiceSchemeController');

    //Print Labels
    Route::get('/labels/show', 'LabelsController@show');
    Route::get('/labels/add-product-row', 'LabelsController@addProductRow');
    Route::get('/labels/preview', 'LabelsController@preview');
    Route::get('/labels/preview_purchase', 'LabelsController@preview_purchase');

    //Reports...
    Route::get('/reports/purchase-report', 'ReportController@purchaseReport');
    Route::get('/reports/sale-report', 'ReportController@saleReport');
    Route::get('/reports/service-staff-report', 'ReportController@getServiceStaffReport');
    Route::get('/reports/service-staff-line-orders', 'ReportController@serviceStaffLineOrders');
    Route::get('/reports/table-report', 'ReportController@getTableReport');
    Route::get('/reports/profit-loss', 'ReportController@getProfitLoss');
    Route::get('/reports/get-opening-stock', 'ReportController@getOpeningStock');
    Route::get('/reports/purchase-sell', 'ReportController@getPurchaseSell');
    Route::get('/reports/customer-supplier', 'ReportController@getCustomerSuppliers');
    Route::get('/reports/stock-report', 'ReportController@getStockReport');
    Route::post('/reports/get-stores-by-locations', 'ReportController@getStoresByLocations')->name('getStoresByLocationsStockReport');

    Route::get('/reports/stock-details', 'ReportController@getStockDetails');
    Route::get('/reports/tax-report', 'ReportController@getTaxReport');
    Route::get('/reports/tax-sell-purchase-report', 'ReportController@getTaxSellPurchaseReport');
    Route::get('/reports/tax-details', 'ReportController@getTaxDetails');
    Route::get('/reports/trending-products', 'ReportController@getTrendingProducts');
    Route::get('/reports/expense-report', 'ReportController@getExpenseReport');
    Route::get('/reports/stock-adjustment-report', 'ReportController@getStockAdjustmentReport');
    Route::get('/reports/register-report', 'ReportController@getRegisterReport');
    Route::get('/reports/sales-representative-report', 'ReportController@getSalesRepresentativeReport');
    Route::get('/reports/sales-representative-total-expense', 'ReportController@getSalesRepresentativeTotalExpense');
    Route::get('/reports/sales-representative-total-sell', 'ReportController@getSalesRepresentativeTotalSell');
    Route::get('/reports/sales-representative-total-commission', 'ReportController@getSalesRepresentativeTotalCommission');
    Route::get('/reports/stock-expiry', 'ReportController@getStockExpiryReport');
    Route::get('/reports/stock-expiry-edit-modal/{purchase_line_id}', 'ReportController@getStockExpiryReportEditModal');
    Route::post('/reports/stock-expiry-update', 'ReportController@updateStockExpiryReport')->name('updateStockExpiryReport');
    Route::get('/reports/customer-group', 'ReportController@getCustomerGroup');
    Route::get('/reports/product-purchase-report', 'ReportController@getproductPurchaseReport');
    Route::get('/reports/product-sell-report', 'ReportController@getproductSellReport');

    Route::get('/reports/product-sell-return-report', 'ReportController@getproductSellReturnReport');
    Route::get('/reports/product-sell-report-with-purchase', 'ReportController@getproductSellReportWithPurchase');
    Route::get('/reports/product-sell-grouped-report', 'ReportController@getproductSellGroupedReport');
    Route::get('/reports/lot-report', 'ReportController@getLotReport');
    Route::get('/reports/purchase-payment-report', 'ReportController@purchasePaymentReport');
    Route::get('/reports/sell-payment-report', 'ReportController@sellPaymentReport');
    Route::get('/reports/product-stock-details', 'ReportController@productStockDetails');
    Route::get('/reports/adjust-product-stock', 'ReportController@adjustProductStock');
    Route::get('/reports/get-profit/{by?}', 'ReportController@getProfit');
    Route::get('/reports/items-report', 'ReportController@itemsReport');
    Route::get('/reports/get-stock-value', 'ReportController@getStockValue');


    /* Report for sell by eng mohamed ali*/
    Route::get('/reports/getsells', 'ReportController@getsells');

    Route::get('business-location/activate-deactivate/{location_id}', 'BusinessLocationController@activateDeactivateLocation');

    //Business Location Settings...
    Route::prefix('business-location/{location_id}')->name('location.')->group(function () {
        Route::get('settings', 'LocationSettingsController@index')->name('settings');
        Route::post('settings', 'LocationSettingsController@updateSettings')->name('settings_update');
    });

    //Business Locations...
    Route::post('business-location/check-location-id', 'BusinessLocationController@checkLocationId');
    Route::resource('business-location', 'BusinessLocationController');

    //Invoice layouts..
    Route::resource('invoice-layouts', 'InvoiceLayoutController');

    //Expense Categories...
    Route::resource('expense-categories', 'ExpenseCategoryController');

    //Expenses...
    Route::resource('expenses', 'ExpenseController');

    //Transaction payments...
    // Route::get('/payments/opening-balance/{contact_id}', 'TransactionPaymentController@getOpeningBalancePayments');
    Route::get('/payments/show-child-payments/{payment_id}', 'TransactionPaymentController@showChildPayments');
    Route::get('/payments/view-payment/{payment_id}', 'TransactionPaymentController@viewPayment');
    Route::get('/payments/add_payment/{transaction_id}', 'TransactionPaymentController@addPayment');
    Route::get('/payments/pay-contact-due/{contact_id}', 'TransactionPaymentController@getPayContactDue');
    Route::post('/payments/pay-contact-due', 'TransactionPaymentController@postPayContactDue');
    Route::resource('payments', 'TransactionPaymentController');

    //Printers...
    Route::resource('printers', 'PrinterController');


    // stock adjustment
    Route::get('/stock-adjustments/remove-expired-stock/{purchase_line_id}', 'StockAdjustmentController@removeExpiredStock');
    Route::post('/stock-adjustments/get_product_row', 'StockAdjustmentController@getProductRow');
    Route::post('/stock-adjustments/get-stores-by-locations', 'StockAdjustmentController@getStoresByLocations')->name('getStoresByLocationsStockAdjustment');
    Route::resource('stock-adjustments', 'StockAdjustmentController');
    Route::get('/cash-register/register-details', 'CashRegisterController@getRegisterDetails');
    Route::get('/cash-register/close-register/{id?}', 'CashRegisterController@getCloseRegister');
    Route::post('/cash-register/close-register', 'CashRegisterController@postCloseRegister');
    Route::resource('cash-register', 'CashRegisterController');

    //Import products
    Route::get('/import-products', 'ImportProductsController@index');
    Route::post('/import-products/store', 'ImportProductsController@store');

    //Sales Commission Agent
    Route::resource('sales-commission-agents', 'SalesCommissionAgentController');

    //Stock get_products      
    Route::get('stock-transfers/print/{id}', 'StockTransferController@printInvoice');
    Route::post('stock-transfers/update-status/{id}', 'StockTransferController@updateStatus');
    Route::post('stock-transfers/get-stores-by-locations', 'StockTransferController@getStoresByLocations')->name('getStoresByLocationsStockTransfer');
    Route::resource('stock-transfers', 'StockTransferController');

    Route::get('/opening-stock/add/{product_id}', 'OpeningStockController@add');
    Route::post('/opening-stock/save', 'OpeningStockController@save');


    //===stocktaking
    Route::any('/stocktacking', 'StocktackingController@index')->name('home');
    Route::any('/stocktacking/create', 'StocktackingController@create')->name('stocktaking.create');
    Route::any('/stocktacking/store', 'StocktackingController@store')->name('stocktaking.store');

    Route::any('/stocktacking/transaction/{id}', 'StocktackingController@transaction')->name('stocktaking.transaction');

    Route::any('/stocktacking/report_plus/{id}', 'StocktackingController@report_plus')->name('report_plus');
    Route::any('/stocktacking/report_minus/{id}', 'StocktackingController@report_minus')->name('report_minus');
    Route::any('/stocktacking/report/{id}', 'StocktackingController@report')->name('stocktaking.report');
    Route::any('/stocktacking/not-tacking-report/{id}', 'StocktackingController@not_tacking_report')->name('stocktaking.not_tacking');
    Route::any('/stocktacking/changeStatus/{id}/{status}', 'StocktackingController@changeStatus')->name('stocktaking.changeStatus');
    Route::any('/stocktacking/transaction_ajax_get', 'StocktackingController@transaction_ajax_get')->name('stocktaking.transaction_ajax_get');
    Route::any('/stocktacking/transaction_ajax_post', 'StocktackingController@transaction_ajax_post')->name('stocktaking.transaction_ajax_post');
    Route::any('/stocktacking/Stock_liquidation', 'StocktackingController@Stock_liquidation')->name('stocktaking.Stock_liquidation');
    Route::any('/stocktacking/delete_from_stocktacking', 'StocktackingController@delete_from_stocktacking')->name('stocktaking.delete_from_stocktacking');
    Route::any('/stocktacking/get_last_product', 'StocktackingController@get_last_product')->name('stocktaking.get_last_product');




    //Customer Groups
    Route::resource('customer-group', 'CustomerGroupController');

    //Import opening stock
    Route::get('/import-opening-stock', 'ImportOpeningStockController@index');
    Route::post('/import-opening-stock/store', 'ImportOpeningStockController@store');

    //Sell return
    Route::resource('sell-return', 'SellReturnController');
    Route::get('sell-return/get-product-row', 'SellReturnController@getProductRow');
    Route::get('/sell-return/print/{id}', 'SellReturnController@printInvoice');
    Route::get('/sell-return/add/{id}', 'SellReturnController@add');
    Route::post('/sell-return/get-stores-by-locations-', 'SellReturnController@getStoresByLocations')->name('getStoresByLocationsSellReturn');



    //Backup
    Route::get('backup/download/{file_name}', 'BackUpController@download');
    Route::get('backup/delete/{file_name}', 'BackUpController@delete');
    Route::resource('backup', 'BackUpController', [
        'only' => [
            'index',
            'create',
            'store'
        ]
    ]);

    Route::get('selling-price-group/activate-deactivate/{id}', 'SellingPriceGroupController@activateDeactivate');
    Route::get('export-selling-price-group', 'SellingPriceGroupController@export');
    Route::post('import-selling-price-group', 'SellingPriceGroupController@import');

    Route::resource('selling-price-group', 'SellingPriceGroupController');

    Route::resource('notification-templates', 'NotificationTemplateController')->only(['index', 'store']);
    Route::get('notification/get-template/{transaction_id}/{template_for}', 'NotificationController@getTemplate');
    Route::post('notification/send', 'NotificationController@send');

    Route::post('/purchase-return/update', 'CombinedPurchaseReturnController@update');
    Route::get('/purchase-return/edit/{id}', 'CombinedPurchaseReturnController@edit');
    Route::post('/purchase-return/save', 'CombinedPurchaseReturnController@save');
    Route::post('/purchase-return/get_product_row', 'CombinedPurchaseReturnController@getProductRow');
    Route::get('/purchase-return/create', 'CombinedPurchaseReturnController@create');
    Route::get('/purchase-return/add/{id}', 'PurchaseReturnController@add');
    Route::post('/purchase-return/get-stores-by-locations-', 'PurchaseReturnController@getStoresByLocations')->name('getStoresByLocationsPurchaseReturn');

    Route::resource('/purchase-return', 'PurchaseReturnController', ['except' => ['create']]);

    Route::get('/discount/activate/{id}', 'DiscountController@activate');
    Route::post('/discount/mass-deactivate', 'DiscountController@massDeactivate');
    Route::resource('discount', 'DiscountController');

    Route::group(['prefix' => 'account'], function () {
        Route::resource('/account', 'AccountController');
        Route::get('/fund-transfer/{id}', 'AccountController@getFundTransfer');
        Route::post('/fund-transfer', 'AccountController@postFundTransfer');
        Route::get('/deposit/{id}', 'AccountController@getDeposit');
        Route::post('/deposit', 'AccountController@postDeposit');
        Route::get('/close/{id}', 'AccountController@close');
        Route::get('/activate/{id}', 'AccountController@activate');
        Route::get('/delete-account-transaction/{id}', 'AccountController@destroyAccountTransaction');
        Route::get('/get-account-balance/{id}', 'AccountController@getAccountBalance');
        Route::get('/balance-sheet', 'AccountReportsController@balanceSheet');
        Route::get('/trial-balance', 'AccountReportsController@trialBalance');
        Route::get('/payment-account-report', 'AccountReportsController@paymentAccountReport');
        Route::get('/link-account/{id}', 'AccountReportsController@getLinkAccount');
        Route::post('/link-account', 'AccountReportsController@postLinkAccount');
        Route::get('/cash-flow', 'AccountController@cashFlow');
    });

    Route::group(['prefix' => 'ac/'], function () {
        // --------------------------------------------------------------------------------------------------- \\    
        Route::get('/import-master', [ImportAcMasterController::class, 'index'])->name('ac.import-master.index');
        Route::post('/import-master/store', [ImportAcMasterController::class, 'store'])->name('ac.import-master.store');
        Route::get('master/export', [AcMasterController::class, 'export'])->name('ac.master.export');
        // --------------------------------------------------------------------------------------------------- \\    
        Route::resource('master', 'AcMasterController');
        // --------------------------------------------------------------------------------------------------- \\    
        Route::get('payments/{type}', 'PaymentController@index')->where('type', 'sales|purchases');
        Route::get('payments/{payment}', 'PaymentController@show');
        Route::get('payment/{payment}/print{ext?}', 'PaymentController@print')->where('ext', '\.pdf');
        Route::get('payments/{type}/create', 'PaymentController@create');
        Route::post('payments/{type}', 'PaymentController@store');
        Route::delete('payments/{payment}', 'PaymentController@destroy');
        Route::get('journal_entry/journal_entry_row', 'AcJournalEntryController@journal_entry_row');

        // ---------------------------------------------------
        Route::get('periods/open_period', 'PeriodController@open_period');
        Route::get('periods/close_period', 'PeriodController@close_period');
        Route::get('periods/store_account_period', 'PeriodController@store_account_period');
        Route::get('periods', 'PeriodController@index')->name('ac_journal.import.index');
        // --------------------------------------------------------------------------------------------------- \\    
        Route::get('journal_entry/import', 'AcJournalEntryController@importView')->name('ac_journal.import.view');
        Route::post('journal_entry/import', 'AcJournalEntryController@import')->name('ac_journal.import.post');
        // --------------------------------------------------------------------------------------------------- \\    
        Route::get('journal_entry/journal_entry_row_open_account', 'AcJournalEntryController@journal_entry_row_open_account');

        Route::match(['get', 'post'], 'journal_entry/create_open_account_entry', 'AcJournalEntryController@createOpenAccountEntry');
        Route::post('store_open_account_entry', 'AcJournalEntryController@storeOpenAccountEntry');

        Route::get('journal_entry/{id}/edit_open_account_entry', 'AcJournalEntryController@editOpenAccountEntry');
        Route::put('journal_entry/{id}/update_open_account_entry', 'AcJournalEntryController@updateOpenAccountEntry');

        Route::get('journal_entries/opening_balance', 'AcJournalEntryController@openingBalance');
        Route::get('journal_entries/opening_balance/{type}/create', 'AcJournalEntryController@createOpeningBalance')->name('opening_balance.create');
        Route::post('journal_entries/opening_balance/{type}', 'AcJournalEntryController@storeOpeningBalance')->name('opening_balance.store');
        Route::get('opening_entries', 'OpeningEntryController@index');
        Route::resource('journal_entry', 'AcJournalEntryController');

        Route::group(['prefix' => 'reports/'], function () {
            Route::get('journal-ledger-report', 'AcReportController@JournalLedgerReport')->name('ac_reports_journal-ledger-report');
            Route::get('account-statement-report-client', 'AcReportController@AccountStatementReportClient')->name('ac_reports_account-statement-report_client');
            Route::get('account-statement-report-supplier', 'AcReportController@AccountStatementReportSupplier')->name('ac_reports_account-statement-report_supplier');

            Route::get('account-statement-report', 'AcReportController@AccountStatementReport')->name('ac_reports_account-statement-report');
            Route::get('trial-balance-report', 'AcReportController@TrialBalanceReport')->name('ac_reports_trial-balance-report');
            Route::get('income-statement-report', 'AcReportController@IncomeStatementReport')->name('ac_reports_income-statement-report');
            Route::get('balance-sheet-report', 'AcReportController@BalanceSheetReport')->name('ac_reports_balance-sheet-report');

            //ajax
            Route::post('get_filter_type_data_list', 'AcReportController@getFilterTypeDataList');
        });

        Route::get('accounts_routing', 'AcRoutingAccountsController@index')->name('accounting.routing.index');
        Route::post('accounts_routing/{type}', 'AcRoutingAccountsController@update')->name('accounting.routing.update');

        Route::resource('cost_cen_branche', 'AcCostCenBrancheController');

        Route::resource('cost_cen_field_add', 'AcCostCenFieldAddController');
    });
    Route::group(['prefix' => 'ac/'], function () {

        //ajax
        Route::post('get_asset_classes_details', 'AcAssetController@getAssetClassesDetails');

        Route::resource('class_asset', 'AcAssetClassController');
        Route::resource('asset', 'AcAssetController');
    });

    Route::resource('account-types', 'AccountTypeController');

    //Restaurant module
    Route::group(['prefix' => 'modules'], function () {
        Route::resource('tables', 'Restaurant\TableController');
        Route::resource('modifiers', 'Restaurant\ModifierSetsController');
        //Map modifier to products
        Route::get('/product-modifiers/{id}/edit', 'Restaurant\ProductModifierSetController@edit');
        Route::post('/product-modifiers/{id}/update', 'Restaurant\ProductModifierSetController@update');
        Route::get('/product-modifiers/product-row/{product_id}', 'Restaurant\ProductModifierSetController@product_row');
        Route::get('/add-selected-modifiers', 'Restaurant\ProductModifierSetController@add_selected_modifiers');


        Route::get('/orders', 'Restaurant\OrderController@index');
        Route::get('/orders/mark-as-served/{id}', 'Restaurant\OrderController@markAsServed');
        Route::get('/data/get-pos-details', 'Restaurant\DataController@getPosDetails');
        Route::get('/orders/mark-line-order-as-served/{id}', 'Restaurant\OrderController@markLineOrderAsServed');


        Route::get('/kitchen', 'Restaurant\KitchenController@index');
        Route::get('/kitchen/mark-as-cooked/{id}', 'Restaurant\KitchenController@markAsCooked');
        Route::post('/refresh-orders-list', 'Restaurant\KitchenController@refreshOrdersList');
        Route::post('/refresh-line-orders-list', 'Restaurant\KitchenController@refreshLineOrdersList');
        // new form eng ali 20-4-2021

        /* Route::get('/orders', 'Restaurant\KitchenController@orders');*/
        Route::get('/kitchen_order', 'Restaurant\KitchenController@index_order');
        Route::get('/setorderstatus', 'Restaurant\KitchenController@setorderstatus');
        Route::get('/kitchen/create', 'Restaurant\KitchenController@create');
        Route::post('/kitchen/store', 'Restaurant\KitchenController@store');
        Route::get('/kitchen/edit/{id}', 'Restaurant\KitchenController@edit');
        Route::post('/kitchen/update', 'Restaurant\KitchenController@update');
        Route::post('/kitchen/delete/{id}', 'Restaurant\KitchenController@delete');
        Route::get('/kitchen_products', 'Restaurant\KitchenController@products');
        Route::get('/kitchen/product_add', 'Restaurant\KitchenController@product_add');
        Route::post('/kitchen/addtokitchen', 'Restaurant\KitchenController@addtokitchen');
        Route::post('/kitchen/removefromkitchen/{id}', 'Restaurant\KitchenController@removefromkitchen');
        // end 20-4-2021

    });



    Route::get('bookings/get-todays-bookings', 'Restaurant\BookingController@getTodaysBookings');
    Route::resource('bookings', 'Restaurant\BookingController');

    // End of Restaurant

    Route::resource('types-of-service', 'TypesOfServiceController');
    Route::get('sells/edit-shipping/{id}', 'SellController@editShipping');
    Route::put('sells/update-shipping/{id}', 'SellController@updateShipping');
    Route::get('shipments', 'SellController@shipments');

    Route::post('upload-module', 'Install\ModulesController@uploadModule');
    Route::resource('manage-modules', 'Install\ModulesController')->only(['index', 'destroy', 'update']);


    Route::resource('warranties', 'WarrantyController');

    Route::resource('dashboard-configurator', 'DashboardConfiguratorController')
        ->only(['edit', 'update']);

    Route::get('view-media/{model_id}', 'SellController@viewMedia');

    //common controller for document & note
    Route::get('get-document-note-page', 'DocumentAndNoteController@getDocAndNoteIndexPage');
    Route::post('post-document-upload', 'DocumentAndNoteController@postMedia');
    Route::resource('note-documents', 'DocumentAndNoteController');
});


Route::middleware(['EcomApi'])->prefix('api/ecom')->group(function () {
    Route::get('products/{id?}', 'ProductController@getProductsApi');
    /* Route::get('categories', 'CategoryController@getCategoriesApi');*/
    Route::get('brands', 'BrandController@getBrandsApi');
    Route::post('customers', 'ContactController@postCustomersApi');
    Route::get('settings', 'BusinessController@getEcomSettings');
    Route::get('variations', 'ProductController@getVariationsApi');
    Route::post('orders', 'SellPosController@placeOrdersApi');
});

//common route
Route::middleware(['auth'])->group(function () {
    Route::get('/logout', 'Auth\LoginController@logout')->name('logout');
});
// Route::middleware(['auth'])->group(function () {
//     Route::get('/logout', \App\Http\Controllers\Auth\CustomLogoutController::class)->name('logout');
// });

Route::middleware([
    'setData',
    'auth',
    'SetSessionData',
    'language',
    'timezone'
])->group(function () {
    Route::get('/load-more-notifications', 'HomeController@loadMoreNotifications');
    Route::get('/get-total-unread', 'HomeController@getTotalUnreadNotifications');
    Route::get('/purchases/print/{id}', 'PurchaseController@printInvoice');
    Route::get('/purchases/{id}', 'PurchaseController@show');
    Route::get('/sells/{id}', 'SellController@show');
    Route::get('/sells/{transaction_id}/print', 'SellPosController@printInvoice')->name('sell.printInvoice');
    Route::get('/sells/invoice-url/{id}', 'SellPosController@showInvoiceUrl');
    Route::get('/show-notification/{id}', 'HomeController@showNotification');
});


/* add by eng mohamed ali
this route is opend without auth to view product  */
Route::middleware([

    'setData',
    'auth',
    'SetSessionData',
    'AdminSidebarMenu',
    'timezone'


])->group(function () {
    Route::get('/gallery/gallery', 'ProductGallery@gallery');
    Route::get('/gallery/setting', 'ProductGallery@setting');
    Route::get('/gallery/stock_report', 'ProductGallery@stock_report');
    Route::post('/gallery/store', 'ProductGallery@update');
    Route::get('/gallery/export', 'ProductGallery@export');
    Route::post('/gallery/get-stores-by-locations', 'ProductGallery@getStoresByLocations')->name('getStoresByLocationsGallery');
    Route::get('reports/activity-log', 'ReportController@activityLog');
});





/* local inventory   */
Route::get('/{slug}', 'ProductGallery@inventory');
/* get inventory products with slug  used by ajax*/
Route::get('/product/slug', 'ProductGallery@inventory');

Route::get('/singlproduct/{id}/{name?}', 'ProductGallery@singlproduct');
