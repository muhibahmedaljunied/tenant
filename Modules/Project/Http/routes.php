<?php

// Route::group(['middleware' => [
//     'web',
//     'authh',
//     'SetSessionData',
//     'auth',
//     'language',
//     'timezone',
//     'AdminSidebarMenu'
// ], 'prefix' => 'project', 'namespace' => 'Modules\Project\Http\Controllers'], function () {
//     Route::put('project/{id}/post-status', 'ProjectController@postProjectStatus');
//     Route::put('project-settings', 'ProjectController@postSettings');
//     Route::resource('project', 'ProjectController');
//     Route::resource('project-task', 'TaskController');
//     Route::get('project-task-get-status', 'TaskController@getTaskStatus');
//     Route::put('project-task/{id}/post-status', 'TaskController@postTaskStatus');
//     Route::put('project-task/{id}/post-description', 'TaskController@postTaskDescription');
//     Route::resource('project-task-comment', 'TaskCommentController');
//     Route::post('post-media-dropzone-upload', 'TaskCommentController@postMedia');
//     Route::resource('project-task-time-logs', 'ProjectTimeLogController');
//     Route::resource('activities', 'ActivityController')->only(['index']);
//     Route::get('project-invoice-tax-report', 'InvoiceController@getProjectInvoiceTaxReport');
//     Route::resource('invoice', 'InvoiceController');
//     Route::get('project-employee-timelog-reports', 'ReportController@getEmployeeTimeLogReport');
//     Route::get('project-timelog-reports', 'ReportController@getProjectTimeLogReport');
//     Route::get('project-reports', 'ReportController@index');

//     Route::get('/install', 'InstallController@index');
//     Route::post('/install', 'InstallController@install');
//     Route::get('/install/uninstall', 'InstallController@uninstall');
//     Route::get('/install/update', 'InstallController@update');
// });



// ------------

use Modules\Project\Http\Controllers\ProjectController;
use Modules\Project\Http\Controllers\ReportController;
use Modules\Project\Http\Controllers\TaskController;

Route::middleware([
    'web',
    'authh',
    'SetSessionData',
    'auth',
    'language',
    'timezone',
    'AdminSidebarMenu'
])->group(function () {

    Route::group(['prefix' => 'project'], function () {

        Route::put('project/{id}/post-status',[ProjectController::class,'postProjectStatus']);
        Route::put('project-settings', 'ProjectController@postSettings');
        // Route::resource('project', 'ProjectController');
        
        Route::get('project', [ProjectController::class,'index'])->name('project-index');
        Route::get('project-create', [ProjectController::class,'create'])->name('project-create');


        // Route::resource('project-task', 'TaskController');
        Route::get('project-task',[TaskController::class,'index'])->name('task-index');

        Route::get('project-task-get-status', 'TaskController@getTaskStatus');
        Route::put('project-task/{id}/post-status', 'TaskController@postTaskStatus');
        Route::put('project-task/{id}/post-description', 'TaskController@postTaskDescription');
        Route::resource('project-task-comment', 'TaskCommentController');
        Route::post('post-media-dropzone-upload', 'TaskCommentController@postMedia');
        Route::resource('project-task-time-logs', 'ProjectTimeLogController');
        Route::resource('activities', 'ActivityController')->only(['index']);
        Route::get('project-invoice-tax-report', 'InvoiceController@getProjectInvoiceTaxReport');
        Route::resource('invoice', 'InvoiceController');
        Route::get('project-employee-timelog-reports', 'ReportController@getEmployeeTimeLogReport');
        Route::get('project-timelog-reports', 'ReportController@getProjectTimeLogReport');
        // Route::get('project-reports', 'ReportController@index');
        Route::get('project-reports', [ReportController::class,'index'])->name('report-index');
        Route::get('project-reports-getEmployeeTimeLogReport', [ReportController::class,'getEmployeeTimeLogReport'])->name('report-getEmployeeTimeLogReport');
        Route::get('project-reports-getProjectTimeLogReport', [ReportController::class,'getProjectTimeLogReport'])->name('report-getProjectTimeLogReport');


        Route::get('/install', 'InstallController@index');
        Route::post('/install', 'InstallController@install');
        Route::get('/install/uninstall', 'InstallController@uninstall');
        Route::get('/install/update', 'InstallController@update');
    });
});
