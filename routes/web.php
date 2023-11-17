<?php

use App\Http\Controllers\AgendaController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobDeskController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\PermissionRequestController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Auth::routes();
Route::middleware(['auth'])->group(function () {
    // Task Route
    Route::resource('tasks', TaskController::class);
    Route::post('tasks/mark-as-complete/{task}', [TaskController::class, 'markAsComplete'])->name('tasks.markAsComplete');
    Route::post('tasks/mark-as-pending/{task}', [TaskController::class, 'markAsPending'])->name('tasks.markAsPending');

    // Assignment Route
    Route::resource('assignments', AssignmentController::class);
    Route::post('assignments/mark-as-complete/{assignment}', [AssignmentController::class, 'markAsComplete'])->name('assignments.markAsComplete');
    Route::post('/assignments/report-kendala/{assignment}', [AssignmentController::class, 'reportKendala'])->name('assignments.report-kendala');
    // Permission Request Route
    Route::resource('permissionrequest', PermissionRequestController::class);
    Route::patch('/permissionrequests/{permissionRequest}/approve', [PermissionRequestController::class, 'approve'])->name('permissionrequest.approve');
    Route::patch('/permissionrequests/{permissionRequest}/reject', [PermissionRequestController::class, 'reject'])->name('permissionrequests.reject');

    // Generate Report Route
    Route::get('report/weekly', [ReportController::class, 'generateWeeklyReport'])->name('report.generateWeeklyReport');
    Route::get('report/monthly', [ReportController::class, 'generateMonthlyReport'])->name('report.generateMonthlyReport');
    Route::post('/announcements/mark-all-as-read', 'AnnouncementController@markAllAsRead')->name('announcements.markAllAsRead');
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::resource('announcements', AnnouncementController::class);
    Route::resource('profile', ProfileController::class);
    Route::resource('jobdesks', JobDeskController::class);
    Route::resource('meetings', MeetingController::class);
    Route::resource('agendas', AgendaController::class);
    Route::get('agendas/{id}/generate', [AgendaController::class, 'generateAgenda'])->name('agendas.print');
});
