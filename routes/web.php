<?php

use App\Http\Controllers\AgendaController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\EducationHistoryController;
use App\Http\Controllers\EmploymentDetailController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobDeskController;
use App\Http\Controllers\LogAgendaController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\PermissionRequestController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SwitchUserController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\UnitKerjaController;
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
    Route::post('assignments/mark-as-pending/{assignment}', [AssignmentController::class, 'markAsPending'])->name('assignments.markAsPending');
    // Permission Request Route
    Route::resource('permissionrequest', PermissionRequestController::class);
    Route::patch('/permissionrequests/{permissionRequest}/approve', [PermissionRequestController::class, 'approve'])->name('permissionrequest.approve');
    Route::patch('/permissionrequests/{permissionRequest}/reject', [PermissionRequestController::class, 'reject'])->name('permissionrequests.reject');

    // Generate Report Route
    Route::get('/report/filtered', [ReportController::class, 'filteredReport'])->name('report.filtered');
    Route::get('report/weekly', [ReportController::class, 'generateWeeklyReport'])->name('report.generateWeeklyReport');
    Route::get('report/monthly', [ReportController::class, 'generateMonthlyReport'])->name('report.generateMonthlyReport');
    Route::post('/announcements/mark-all-as-read', 'AnnouncementController@markAllAsRead')->name('announcements.markAllAsRead');
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::resource('announcements', AnnouncementController::class);
    Route::resource('profile', ProfileController::class);
    Route::resource('jobdesks', JobDeskController::class);
    Route::resource('meetings', MeetingController::class);

    //Employment Detail
    Route::resource('employment-detail', EmploymentDetailController::class)
        ->parameters(['employment-detail' => 'employment_detail:employee_number']);
    Route::get('/employment-detail/{employment_detail:employee_number}/cetak', [EmploymentDetailController::class, 'cetak'])->name('employment-detail.cetak');


    //Education
    Route::resource('education', EducationHistoryController::class);
    //Training
    Route::resource('training', TrainingController::class);

    // Agenda dan Log agenda Routes
    Route::resource('agendas', AgendaController::class);
    Route::resource('logAgendas', LogAgendaController::class);
    Route::get('agendas/{id}/generate', [AgendaController::class, 'generateAgenda'])->name('agendas.print');
    Route::patch('agendas/{id}/update-status', [AgendaController::class, 'updateStatus'])->name('agendas.updateStatus');

    //Switch Unit User
    Route::post('/switch-unit', [SwitchUserController::class, 'switchUnit'])->name('switchUnit');

    //ROute Assign User
    Route::resource('Unit', UnitKerjaController::class);
    Route::get('/unit/{unit}/assign-users', [UnitKerjaController::class, 'assignForm'])->name('unit.assign.form');
    Route::post('/unit/{unit}/assign-users', [UnitKerjaController::class, 'assignUsers'])->name('unit.assign.users');


    //Pegawai per Unit
    Route::get('/unit/{unitId}/pegawai', [HomeController::class, 'showPegawaiByUnit'])->name('unit.pegawai');


    //Import Pegawai
    Route::post('/import-users', [HomeController::class, 'import'])->name('users.import');
});
