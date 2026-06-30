<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

// Home → Login redirect
Route::get('/', function () {
    return redirect('/login');
});

// This fixes the Breeze "dashboard" route error
Route::middleware('auth')->get('/dashboard', function () {
    $type = auth()->user()->user_type;
    return redirect('/dashboard/' . $type);
})->name('dashboard');

// Student routes
Route::middleware(['auth', 'role:student'])->group(function () {
    Route::get('/dashboard/student', [DashboardController::class, 'student'])
         ->name('dashboard.student');
});

// Lecturer routes
Route::middleware(['auth', 'role:lecturer'])->group(function () {
    Route::get('/dashboard/lecturer', [DashboardController::class, 'lecturer'])
         ->name('dashboard.lecturer');
});

// HOD routes
Route::middleware(['auth', 'role:hod'])->group(function () {
    Route::get('/dashboard/hod', [DashboardController::class, 'hod'])
         ->name('dashboard.hod');
});



use App\Http\Controllers\ProjectController;

// ── Student Project Routes ──────────────────────
Route::middleware(['auth','role:student'])->group(function () {
    Route::get('/projects', [ProjectController::class, 'index'])
         ->name('projects.index');
    Route::get('/projects/create', [ProjectController::class, 'create'])
         ->name('projects.create');
    Route::post('/projects', [ProjectController::class, 'store'])
         ->name('projects.store');
    Route::post('/projects/{id}/update', [ProjectController::class, 'addUpdate'])
         ->name('projects.addUpdate');
});

// ── Lecturer Project Routes ─────────────────────
Route::middleware(['auth','role:lecturer'])->group(function () {
    Route::get('/projects/supervise', [ProjectController::class, 'supervise'])
         ->name('projects.supervise');
    Route::get('/projects/unassigned', [ProjectController::class, 'unassigned'])
         ->name('projects.unassigned');
    Route::post('/projects/request-supervision',
         [ProjectController::class, 'requestSupervision'])
         ->name('projects.requestSupervision');
});

// ── HOD Project Routes ──────────────────────────
Route::middleware(['auth','role:hod'])->group(function () {
    Route::get('/projects/all', [ProjectController::class, 'allProjects'])
         ->name('projects.all');
    Route::post('/projects/{id}/assign-supervisor',
         [ProjectController::class, 'assignSupervisor'])
         ->name('projects.assignSupervisor');
    Route::get('/projects/supervision-requests',
         [ProjectController::class, 'supervisionRequests'])
         ->name('projects.supervisionRequests');
    Route::post('/projects/supervision-requests/{id}',
         [ProjectController::class, 'handleSupervisionRequest'])
         ->name('projects.handleSupervisionRequest');
});

// ── Shared — View single project ────────────────
Route::middleware('auth')->get('/projects/{id}',
     [ProjectController::class, 'show'])->name('projects.show');


use App\Http\Controllers\ResourceController;

// ── Student & Lecturer Resource Routes ──────────
Route::middleware('auth')->group(function () {
    Route::get('/resources/book',
         [ResourceController::class, 'bookForm'])
         ->name('resources.bookForm');
    Route::post('/resources/book',
         [ResourceController::class, 'book'])
         ->name('resources.book');
    Route::get('/resources/my-bookings',
         [ResourceController::class, 'myBookings'])
         ->name('resources.myBookings');
    Route::post('/resources/return/{id}',
         [ResourceController::class, 'returnResource'])
         ->name('resources.return');
    Route::get('/resources/calendar',
         [ResourceController::class, 'calendar'])
         ->name('resources.calendar');
});

// ── HOD Resource Routes ──────────────────────────
Route::middleware(['auth','role:hod'])->group(function () {
    Route::get('/resources/manage',
         [ResourceController::class, 'manage'])
         ->name('resources.manage');
    Route::post('/resources/manage',
         [ResourceController::class, 'store'])
         ->name('resources.store');
    Route::delete('/resources/{id}',
         [ResourceController::class, 'destroy'])
         ->name('resources.destroy');
    Route::get('/resources/bookings',
         [ResourceController::class, 'manageBookings'])
         ->name('resources.manageBookings');
    Route::post('/resources/bookings/{id}',
         [ResourceController::class, 'handleBooking'])
         ->name('resources.handleBooking');
});

use App\Http\Controllers\LeaveController;

// ── Lecturer Leave Routes ────────────────────────
Route::middleware(['auth','role:lecturer'])->group(function () {
    Route::get('/leave/apply',
         [LeaveController::class, 'applyForm'])
         ->name('leave.applyForm');
    Route::post('/leave/apply',
         [LeaveController::class, 'apply'])
         ->name('leave.apply');
    Route::get('/leave/my-leaves',
         [LeaveController::class, 'myLeaves'])
         ->name('leave.myLeaves');
});

// ── HOD Leave Routes ─────────────────────────────
Route::middleware(['auth','role:hod'])->group(function () {
    Route::get('/leave/manage',
         [LeaveController::class, 'manage'])
         ->name('leave.manage');
    Route::post('/leave/manage/{id}',
         [LeaveController::class, 'handle'])
         ->name('leave.handle');
});

// ── Shared Leave Calendar ────────────────────────
Route::middleware('auth')->get('/leave/calendar',
     [LeaveController::class, 'calendar'])
     ->name('leave.calendar');


use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReportController;

// ── Notifications (all roles) ────────────────────
Route::middleware('auth')->get('/notifications',
    [NotificationController::class, 'index'])
    ->name('notifications.index');

// ── Reports (HOD only) ───────────────────────────
Route::middleware(['auth','role:hod'])->get('/reports',
    [ReportController::class, 'index'])
    ->name('reports.index');

require __DIR__.'/auth.php';