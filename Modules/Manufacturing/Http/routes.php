<?php

// Route::group(['middleware' => [
//     'web',
//     'authh',
//     'SetSessionData',
//     'auth',
//     'language',
//     'timezone',
//     'AdminSidebarMenu'
// ], 'prefix' => 'manufacturing', 'namespace' => 'Modules\Manufacturing\Http\Controllers'], function () {
//     Route::get('/install', 'InstallController@index');
//     Route::post('/install', 'InstallController@install');
//     Route::get('/install/update', 'InstallController@update');
//     Route::get('/install/uninstall', 'InstallController@uninstall');

//     Route::get('/is-recipe-exist/{variation_id}', 'RecipeController@isRecipeExist');
//     Route::get('/ingredient-group-form', 'RecipeController@getIngredientGroupForm');
//     Route::get('/get-recipe-details', 'RecipeController@getRecipeDetails');
//     Route::get('/get-ingredient-row/{variation_id}', 'RecipeController@getIngredientRow');
//     Route::get('/add-ingredient', 'RecipeController@addIngredients');
//     Route::resource('/recipe', 'RecipeController', ['except' => ['edit', 'update']]);
//     Route::resource('/production', 'ProductionController');
//     Route::resource('/settings', 'SettingsController', ['only' => ['index', 'store']]);

//     Route::get('/report', 'ProductionController@getManufacturingReport');

//     Route::post('/update-product-prices', 'RecipeController@updateRecipeProductPrices');
// });

use App\Http\Controllers\Install\InstallController;
use Modules\Manufacturing\Http\Controllers\ProductionController;
use Modules\Manufacturing\Http\Controllers\RecipeController;
use Modules\Manufacturing\Http\Controllers\SettingsController;

Route::middleware([
    'web',
    'authh',
    'SetSessionData',
    'auth',
    'language',
    'timezone',
    'AdminSidebarMenu'
])->group(function () {


    Route::get('/manufacturing/install', [InstallController::class, 'index']);
    Route::post('/manufacturing/install', [InstallController::class, 'install']);
    Route::get('/manufacturing/install/update',  [InstallController::class, 'update']);
    Route::get('/manufacturing/install/uninstall',  [InstallController::class, 'uninstall']);
    Route::get('/manufacturing/is-recipe-exist/{variation_id}', [RecipeController::class, 'isRecipeExist']);
    Route::get('/manufacturing/ingredient-group-form', [RecipeController::class, 'getIngredientGroupForm']);
    Route::get('/manufacturing/get-recipe-details', [RecipeController::class, 'getRecipeDetails']);
    Route::get('/manufacturing/get-ingredient-row/{variation_id}', [RecipeController::class, 'getIngredientRow']);
    Route::get('/manufacturing/add-ingredient', [RecipeController::class, 'addIngredients']);
    Route::get('/manufacturing/recipe-index', [RecipeController::class, 'index'])->name('recipe-index');
    Route::get('/manufacturing/recipe-create', [RecipeController::class, 'create'])->name('recipe-create');
    Route::get('/manufacturing/recipe-addIngredients', [RecipeController::class, 'addIngredients'])->name('recipe-addIngredients');

    Route::get('/manufacturing/production-index', [ProductionController::class, 'index'])->name('production-index');
    Route::get('/manufacturing/production-create', [ProductionController::class, 'create'])->name('production-create');
    Route::post('/manufacturing/production-store', [ProductionController::class, 'store'])->name('production-store');

    Route::get('/manufacturing/settings-index',[SettingsController::class,'index'])->name('settings-index');
    Route::get('/manufacturing/settings-store',[SettingsController::class,'store'])->name('settings-store');
    Route::get('/manufacturing/report', [ProductionController::class, 'getManufacturingReport']);
    Route::post('/manufacturing/update-product-prices', [RecipeController::class, 'updateRecipeProductPrices']);
});
