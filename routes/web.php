<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Employer;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResumeController;
use App\Http\Controllers\Seeker;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public pages
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');
Route::get('/jobs/{job}', [JobController::class, 'show'])->name('jobs.show');

/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

/*
|--------------------------------------------------------------------------
| Any logged-in user
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/resume', [ProfileController::class, 'resume'])->name('profile.resume');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{id}', [NotificationController::class, 'open'])->name('notifications.open');
    Route::post('/notifications/read-all', [NotificationController::class, 'readAll'])->name('notifications.readAll');

    Route::get('/applications/{application}/resume', [ResumeController::class, 'download'])->name('applications.resume');
});

/*
|--------------------------------------------------------------------------
| Employer
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:employer'])->prefix('employer')->name('employer.')->group(function () {
    Route::resource('jobs', Employer\JobController::class)->except('show');
    Route::get('jobs/{job}/applications', [Employer\ApplicationController::class, 'index'])->name('jobs.applications');
    Route::patch('applications/{application}', [Employer\ApplicationController::class, 'update'])->name('applications.update');
});

/*
|--------------------------------------------------------------------------
| Job seeker
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:seeker'])->prefix('seeker')->name('seeker.')->group(function () {
    Route::post('jobs/{job}/apply', [Seeker\ApplicationController::class, 'store'])->name('apply');
    Route::get('applications', [Seeker\ApplicationController::class, 'index'])->name('applications.index');

    Route::get('alerts', [Seeker\AlertController::class, 'index'])->name('alerts.index');
    Route::post('alerts', [Seeker\AlertController::class, 'store'])->name('alerts.store');
    Route::patch('alerts/{alert}/toggle', [Seeker\AlertController::class, 'toggle'])->name('alerts.toggle');
    Route::delete('alerts/{alert}', [Seeker\AlertController::class, 'destroy'])->name('alerts.destroy');
});

/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::get('users', [Admin\UserController::class, 'index'])->name('users.index');
    Route::patch('users/{user}/block', [Admin\UserController::class, 'toggleBlock'])->name('users.block');
    Route::patch('users/{user}/role', [Admin\UserController::class, 'updateRole'])->name('users.role');
    Route::delete('users/{user}', [Admin\UserController::class, 'destroy'])->name('users.destroy');

    Route::get('jobs', [Admin\JobController::class, 'index'])->name('jobs.index');
    Route::patch('jobs/{job}/status', [Admin\JobController::class, 'toggleStatus'])->name('jobs.status');
    Route::delete('jobs/{job}', [Admin\JobController::class, 'destroy'])->name('jobs.destroy');
});
