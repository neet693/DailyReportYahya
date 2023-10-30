<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AssignmentsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('/login');
});
Auth::routes();
Route::middleware(['auth'])->group(function () {
    Route::resource('tasks', TaskController::class);
    Route::resource('announcements', AnnouncementController::class);
    Route::resource('assignments', AssignmentsController::class);
    Route::resource('profile', ProfileController::class);
    Route::get('report/weekly', [ReportController::class, 'generateWeeklyReport'])->name('report.generateWeeklyReport');
    Route::get('report/monthly', [ReportController::class, 'generateMonthlyReport'])->name('report.generateMonthlyReport');
    Route::post('/announcements/mark-all-as-read', 'AnnouncementController@markAllAsRead')->name('announcements.markAllAsRead');
    Route::get('/home', [HomeController::class, 'index'])->name('home');
});
