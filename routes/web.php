<?php

use App\Events\MessageSent;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\EducationHistoryController;
use App\Http\Controllers\EmploymentDetailController;
use App\Http\Controllers\FixedScheduleController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobDeskController;
use App\Http\Controllers\LateNotesController;
use App\Http\Controllers\LogAgendaController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\PermissionRequestController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SuratPeringatanController;
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
    Route::get('/home', [HomeController::class, 'index'])->name('home');
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


    Route::resource('announcements', AnnouncementController::class);
    Route::resource('profile', ProfileController::class);
    Route::resource('jobdesks', JobDeskController::class);

    //MeetingController
    Route::resource('meetings', MeetingController::class);
    Route::post('/meetings/upload-attachment', [MeetingController::class, 'uploadAttachment'])->name('meetings.uploadAttachment');

    //Employment Detail
    Route::resource('employment-detail', EmploymentDetailController::class)
        ->parameters(['employment-detail' => 'employment_detail:employee_number']);
    Route::get('/employment-detail/{employment_detail:employee_number}/cetak', [EmploymentDetailController::class, 'cetak'])->name('employment-detail.cetak');


    //Surat Peringantan
    Route::resource('surat-peringatan', SuratPeringatanController::class);

    //Education
    Route::resource('education', EducationHistoryController::class);
    //Training
    Route::resource('training', TrainingController::class);

    // Agenda dan Log agenda Routes
    Route::resource('agendas', AgendaController::class);
    Route::resource('logAgendas', LogAgendaController::class);
    Route::get('agendas/{id}/generate', [AgendaController::class, 'generateAgenda'])->name('agendas.print');
    Route::patch('agendas/{id}/update-status', [AgendaController::class, 'updateStatus'])->name('agendas.updateStatus');
    Route::get('/agenda/unit', [AgendaController::class, 'agendaByUnit'])->name('agenda.byUnit')->middleware('auth');
    Route::get('/agendas/json', [AgendaController::class, 'json']);



    //Switch Unit User
    Route::post('/switch-unit', [SwitchUserController::class, 'switchUnit'])->name('switchUnit');

    //ROute Assign User
    Route::resource('Unit', UnitKerjaController::class);



    //Pegawai per Unit
    Route::get('/unit/{unitId}/pegawai', [HomeController::class, 'showPegawaiByUnit'])->name('unit.pegawai');

    Route::get('/chat', [MessageController::class, 'index']);
    Route::post('/chat/messages', [MessageController::class, 'fetchMessages']);
    Route::post('/chat/send', [MessageController::class, 'sendMessage']);
    //Route Keterlambatan
    Route::resource('keterlambatan', LateNotesController::class);
});

// Route untuk HRD daftar pegawai
Route::middleware(['auth', 'role:hrd'])->group(function () {
    Route::post('/absensi/import', [AbsensiController::class, 'import'])->name('absensi.import');
    Route::get('pegawai/create', [PegawaiController::class, 'create'])->name('pegawai.create');
    Route::post('pegawai', [PegawaiController::class, 'store'])->name('pegawai.store');
    //Delete Pegawai
    Route::delete('/pegawai/{id}', [HomeController::class, 'destroy'])->name('users.destroy');
    //Import Pegawai
    Route::post('/import-users', [HomeController::class, 'import'])->name('users.import');
});

//Route Fixed Schedule
Route::middleware(['auth', 'role:kepala,pegawai'])->group(function () {
    Route::get('/fixed-schedule/create', [FixedScheduleController::class, 'create'])->name('fixed-schedule.create');
    Route::post('/fixed-schedule', [FixedScheduleController::class, 'store'])->name('fixed-schedule.store');
});

Route::middleware(['auth', 'role:admin,kepala,hrd'])->group(function () {
    Route::get('/employment-detail/create/{employment_detail:employee_number}', [EmploymentDetailController::class, 'create'])
        ->name('employment-detail.create');
    Route::post('/employment-detail', [EmploymentDetailController::class, 'store'])
        ->name('employment-detail.store');
    Route::resource('absensi', AbsensiController::class)->only(['index']);
    //Keterlambatan
    Route::post('/keterlambatan/{slug}/acc', [LateNotesController::class, 'acc'])
        ->name('keterlambatan.acc');
});

Route::middleware(['auth', 'role:admin,kepala'])->group(function () {
    Route::get('/unit/{unit}/assign-users', [UnitKerjaController::class, 'assignForm'])->name('unit.assign.form');
    Route::post('/unit/{unit}/assign-users', [UnitKerjaController::class, 'assignUsers'])->name('unit.assign.users');
});
