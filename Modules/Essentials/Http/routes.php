<?php

// Route::group(['middleware' => ['web', 'authh', 'auth', 'SetSessionData', 'language', 'timezone', 'AdminSidebarMenu'], 'namespace' => 'Modules\Essentials\Http\Controllers'], function () {
//     Route::group(['prefix' => 'essentials'], function () {

//         Route::get('/dashboard', 'DashboardController@essentialsDashboard');
//         Route::get('/install', 'InstallController@index');
//         Route::get('/install/update', 'InstallController@update');
//         Route::get('/install/uninstall', 'InstallController@uninstall');

//         Route::get('/', 'EssentialsController@index');

//         //document controller
//         Route::resource('document', 'DocumentController')->only(['index', 'store', 'destroy', 'show']);
//         Route::get('document/download/{id}', 'DocumentController@download');

//         //document share controller
//         Route::resource('document-share', 'DocumentShareController')->only(['edit', 'update']);

//         //todo controller
//         Route::resource('todo', 'ToDoController');

//         Route::post('todo/add-comment', 'ToDoController@addComment');
//         Route::get('todo/delete-comment/{id}', 'ToDoController@deleteComment');
//         Route::get('todo/delete-document/{id}', 'ToDoController@deleteDocument');
//         Route::post('todo/upload-document', 'ToDoController@uploadDocument');

//         //reminder controller
//         Route::resource('reminder', 'ReminderController')->only(['index', 'store', 'edit', 'update', 'destroy', 'show']);

//         //message controller
//         Route::get('get-new-messages', 'EssentialsMessageController@getNewMessages');
//         Route::resource('messages', 'EssentialsMessageController')->only(['index', 'store', 'destroy']);

//         //Allowance and deduction controller
//         Route::resource('allowance-deduction', 'EssentialsAllowanceAndDeductionController');
//     });

//     Route::group(['prefix' => 'hrm'], function () {
//         Route::get('/dashboard', 'DashboardController@hrmDashboard');
//         Route::resource('/leave-type', 'EssentialsLeaveTypeController');
//         Route::resource('/leave', 'EssentialsLeaveController');
//         Route::post('/change-status', 'EssentialsLeaveController@changeStatus');
//         Route::get('/leave/activity/{id}', 'EssentialsLeaveController@activity');
//         Route::get('/user-leave-summary', 'EssentialsLeaveController@getUserLeaveSummary');

//         Route::get('/settings', 'EssentialsSettingsController@edit');
//         Route::post('/settings', 'EssentialsSettingsController@update');

//         Route::post('/import-attendance', 'AttendanceController@importAttendance');
//         Route::resource('/attendance', 'AttendanceController');
//         Route::post('/clock-in-clock-out', 'AttendanceController@clockInClockOut');

//         Route::post('/validate-clock-in-clock-out', 'AttendanceController@validateClockInClockOut');

//         Route::get('/get-attendance-by-shift', 'AttendanceController@getAttendanceByShift');
//         Route::get('/get-attendance-by-date', 'AttendanceController@getAttendanceByDate');
//         Route::get('/get-attendance-row/{user_id}', 'AttendanceController@getAttendanceRow');

//         Route::get(
//             '/user-attendance-summary',
//             'AttendanceController@getUserAttendanceSummary'
//         );

//         Route::resource('/payroll', 'PayrollController');
//         Route::resource('/holiday', 'EssentialsHolidayController');

//         Route::get('/shift/assign-users/{shift_id}', 'ShiftController@getAssignUsers');
//         Route::post('/shift/assign-users', 'ShiftController@postAssignUsers');
//         Route::resource('/shift', 'ShiftController');
//     });
// });
// ------------------------------------------------------------------------------------

use Modules\Essentials\Entities\EssentialsAllowanceAndDeduction;
use Modules\Essentials\Entities\EssentialsHoliday;
use Modules\Essentials\Http\Controllers\AttendanceController;
use Modules\Essentials\Http\Controllers\DashboardController;
use Modules\Essentials\Http\Controllers\DocumentController;
use Modules\Essentials\Http\Controllers\EssentialsAllowanceAndDeductionController;
use Modules\Essentials\Http\Controllers\EssentialsHolidayController;
use Modules\Essentials\Http\Controllers\EssentialsLeaveController;
use Modules\Essentials\Http\Controllers\EssentialsLeaveTypeController;
use Modules\Essentials\Http\Controllers\EssentialsMessageController;
use Modules\Essentials\Http\Controllers\EssentialsSettingsController;
use Modules\Essentials\Http\Controllers\InstallController;
use Modules\Essentials\Http\Controllers\PayrollController;
use Modules\Essentials\Http\Controllers\ReminderController;
use Modules\Essentials\Http\Controllers\ToDoController;

Route::middleware([
    'web',
    'authh',
    'auth',
    'SetSessionData',
    'language',
    'timezone',
    'AdminSidebarMenu'
])->group(function () {

    Route::group(['prefix' => 'essentials'], function () {
        Route::get('/dashboard', 'DashboardController@essentialsDashboard');
        Route::get('/install', [InstallController::class,'index']);
        Route::get('/install/update',[InstallController::class,'update']);
        Route::get('/install/uninstall',[InstallController::class,'uninstall']);

        Route::get('/', 'EssentialsController@index');

        //document controller
        // Route::resource('document', 'DocumentController')->only(['index', 'store', 'destroy', 'show']);
        Route::get('document', [DocumentController::class,'index'])->name('document-index');

        Route::get('document/download/{id}', 'DocumentController@download');

        //document share controller
        Route::resource('document-share', 'DocumentShareController')->only(['edit', 'update']);

        //todo controller
        // Route::resource('todo', 'ToDoController');
        Route::get('todo-index', [ToDoController::class,'index'])->name('toDo-index');
        Route::get('todo-create', [ToDoController::class,'create'])->name('toDo-create');


        Route::post('todo/add-comment', 'ToDoController@addComment');
        Route::get('todo/delete-comment/{id}', 'ToDoController@deleteComment');
        Route::get('todo/delete-document/{id}', 'ToDoController@deleteDocument');
        Route::post('todo/upload-document', 'ToDoController@uploadDocument');

        //reminder controller
        // Route::resource('reminder', 'ReminderController')->only(['index', 'store', 'edit', 'update', 'destroy', 'show']);
        Route::get('reminder', [ReminderController::class,'index'])->name('reminder-index');

        //message controller
        Route::get('get-new-messages', 'EssentialsMessageController@getNewMessages');
        // Route::resource('messages', 'EssentialsMessageController')->only(['index', 'store', 'destroy']);
        Route::get('messages', [EssentialsMessageController::class,'index'])->name('essentialsMessage-index');

        //Allowance and deduction controller
        // Route::resource('allowance-deduction', 'EssentialsAllowanceAndDeductionController');
        Route::get('allowance-deduction-index', [EssentialsAllowanceAndDeductionController::class,'index'])->name('essentialsAllowanceAndDeduction-index');
        Route::get('allowance-deduction-create', [EssentialsAllowanceAndDeductionController::class,'create'])->name('essentialsAllowanceAndDeduction-create');
        Route::post('allowance-deduction-store', [EssentialsAllowanceAndDeductionController::class,'store'])->name('essentialsAllowanceAndDeduction-store');

    });

    Route::group(['prefix' => 'hrm'], function () {
        Route::get('/dashboard',[DashboardController::class,'hrmDashboard'])->name('dashboard-hrmDashboard');
        // Route::resource('/leave-type', 'EssentialsLeaveTypeController');
        Route::get('/leave-type', [EssentialsLeaveTypeController::class,'index'])->name('essentialsLeaveType-index');
        Route::post('/leave-type', [EssentialsLeaveTypeController::class,'store'])->name('essentialsLeaveType-store');

        // Route::resource('/leave', 'EssentialsLeaveController');
        
        Route::get('/leave-index', [EssentialsLeaveController::class,'index'])->name('essentialsLeave-index');
        Route::get('/leave-create', [EssentialsLeaveController::class,'create'])->name('essentialsLeave-create');
        Route::post('/leave-store', [EssentialsLeaveController::class,'store'])->name('essentialsLeave-store');

        Route::post('/change-status',[EssentialsLeaveController::class,'changeStatus'])->name('essentialsLeave-changeStatus');
        Route::get('/leave/activity/{id}',[EssentialsLeaveController::class,'activity'])->name('essentialsLeave-activity');
        Route::get('/user-leave-summary', [EssentialsLeaveController::class,'getUserLeaveSummary'])->name('essentialsLeave-getUserLeaveSummary');

        Route::get('/settings', [EssentialsSettingsController::class,'edit'])->name('essentialsSettings-edit');
        Route::post('/settings', [EssentialsSettingsController::class,'update'])->name('essentialsSettings-update');

        Route::post('/import-attendance', 'AttendanceController@importAttendance');
        // Route::resource('/attendance', 'AttendanceController');
        Route::get('/attendance', [AttendanceController::class,'index'])->name('attendance-index');
        Route::get('/attendance-create', [AttendanceController::class,'create'])->name('attendance-create');
        Route::post('/attendance-store', [AttendanceController::class,'store'])->name('attendance-store');


        Route::post('/clock-in-clock-out', 'AttendanceController@clockInClockOut')->name('attendance-clockInClockOut');
        // Route::post('/clock-in-clock-out', 'AttendanceController@clockInClockOut');


        Route::post('/validate-clock-in-clock-out', 'AttendanceController@validateClockInClockOut');

        Route::get('/get-attendance-by-shift', 'AttendanceController@getAttendanceByShift');
        Route::get('/get-attendance-by-date', 'AttendanceController@getAttendanceByDate');
        Route::get('/get-attendance-row/{user_id}', 'AttendanceController@getAttendanceRow');

        Route::get(
            '/user-attendance-summary',
            'AttendanceController@getUserAttendanceSummary'
        );

        // Route::resource('/payroll', 'PayrollController');
        Route::get('/payroll',[PayrollController::class,'index'])->name('payroll-index');
        Route::get('/payroll-create',[PayrollController::class,'create'])->name('payroll-create');

        // Route::resource('/holiday', 'EssentialsHolidayController');
        Route::get('/holiday', [EssentialsHolidayController::class,'index'])->name('essentialsHoliday-index');
        Route::get('/holiday-create', [EssentialsHolidayController::class,'create'])->name('essentialsHoliday-create');


        Route::get('/shift/assign-users/{shift_id}', 'ShiftController@getAssignUsers');
        Route::post('/shift/assign-users', 'ShiftController@postAssignUsers');
        Route::resource('/shift', 'ShiftController');
    });
});
