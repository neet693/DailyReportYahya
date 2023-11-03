<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobDeskController;
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
    Route::resource('tasks', TaskController::class);
    Route::resource('announcements', AnnouncementController::class);
    Route::resource('assignments', AssignmentController::class);
    Route::resource('profile', ProfileController::class);
    Route::resource('jobdesks', JobDeskController::class);
    Route::post('assignments/mark-as-complete/{assignment}', [AssignmentController::class, 'markAsComplete'])->name('assignments.markAsComplete');
    Route::post('/assignments/report-kendala/{assignment}', [AssignmentController::class, 'reportKendala'])->name('assignments.report-kendala');
    Route::get('report/weekly', [ReportController::class, 'generateWeeklyReport'])->name('report.generateWeeklyReport');
    Route::get('report/monthly', [ReportController::class, 'generateMonthlyReport'])->name('report.generateMonthlyReport');
    Route::post('/announcements/mark-all-as-read', 'AnnouncementController@markAllAsRead')->name('announcements.markAllAsRead');
    Route::get('/home', [HomeController::class, 'index'])->name('home');
});
