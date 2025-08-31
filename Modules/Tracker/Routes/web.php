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

Route::group(['middleware' => ['web', 'authh', 'auth', 'SetSessionData', 'language', 'timezone', 'AdminSidebarMenu', 'CheckUserLogin'], 'namespace' => '\Modules\Tracker\Http\Controllers', 'prefix' => 'tracker'], function () {
    Route::get('install', 'InstallController@index');
    Route::post('install', 'InstallController@install');
    Route::get('/install/uninstall', 'InstallController@uninstall');

    Route::resource('provinces', 'ProvinceController')->except('show')->names('dm.provinces');
    Route::resource('sectors', 'SectorController')->except('show')->names('dm.sectors');
    Route::resource('distributionAreas', 'DistributionAreaController')->except('show')->names('dm.distributionAreas');
    Route::resource('tracks', 'TrackController')->names('dm.tracks');
    Route::get('map', 'ContactController@map')->name('dm.contacts.map');
});
