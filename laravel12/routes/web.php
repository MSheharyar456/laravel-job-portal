<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\JobSeekerController;
use App\Http\Controllers\EmployerController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ModeratorController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Middleware\PreventBackHistory;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');
Route::get('/jobs/{job}', [JobController::class, 'show'])->name('jobs.show');

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::get('/forgot-password', [PasswordResetController::class, 'requestForm'])->name('password.request');
Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [PasswordResetController::class, 'resetForm'])->name('password.reset');
Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');
Route::get('/email/verify', [EmailVerificationController::class, 'notice'])->middleware('auth')->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])->middleware(['auth', 'signed', 'throttle:6,1'])->name('verification.verify');
Route::post('/email/verification-notification', [EmailVerificationController::class, 'resend'])->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// Job Seeker Routes
Route::middleware(['auth', PreventBackHistory::class, 'role:job_seeker'])->prefix('job-seeker')->name('job-seeker.')->group(function () {
    Route::get('/dashboard', [JobSeekerController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [JobSeekerController::class, 'profile'])->name('profile');
    Route::put('/profile', [JobSeekerController::class, 'updateProfile'])->name('profile.update');
    Route::get('/applications', [JobSeekerController::class, 'applications'])->name('applications');
    Route::get('/jobs/{job}/apply', [JobSeekerController::class, 'showApplyForm'])->name('jobs.apply');
    Route::post('/jobs/{job}/apply', [JobSeekerController::class, 'apply'])->name('jobs.apply.submit');
});

// Employer Routes
Route::middleware(['auth', PreventBackHistory::class, 'role:employer'])->prefix('employer')->name('employer.')->group(function () {
    Route::get('/dashboard', [EmployerController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [EmployerController::class, 'profile'])->name('profile');
    Route::put('/profile', [EmployerController::class, 'updateProfile'])->name('profile.update');
    Route::get('/jobs', [EmployerController::class, 'jobs'])->name('jobs');
    Route::get('/jobs/create', [EmployerController::class, 'createJob'])->name('jobs.create');
    Route::post('/jobs', [EmployerController::class, 'storeJob'])->name('jobs.store');
    Route::get('/jobs/{job}/edit', [EmployerController::class, 'editJob'])->name('jobs.edit');
    Route::put('/jobs/{job}', [EmployerController::class, 'updateJob'])->name('jobs.update');
    Route::delete('/jobs/{job}', [EmployerController::class, 'deleteJob'])->name('jobs.destroy');
    Route::get('/jobs/{job}/applications', [EmployerController::class, 'jobApplications'])->name('jobs.applications');
    Route::put('/applications/{application}', [EmployerController::class, 'updateApplicationStatus'])->name('applications.update');
});

// Admin Routes
Route::middleware(['auth', PreventBackHistory::class, 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::put('/users/{user}/role', [AdminController::class, 'updateUserRole'])->name('users.role');
    Route::put('/users/{user}/status', [AdminController::class, 'updateUserStatus'])->name('users.status');
    Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.destroy');
    Route::get('/jobs', [AdminController::class, 'jobs'])->name('jobs');
    Route::put('/jobs/{job}/status', [AdminController::class, 'updateJobStatus'])->name('jobs.status');
    Route::delete('/jobs/{job}', [AdminController::class, 'deleteJob'])->name('jobs.destroy');
    Route::get('/categories', [AdminController::class, 'categories'])->name('categories');
    Route::post('/categories', [AdminController::class, 'storeCategory'])->name('categories.store');
    Route::put('/categories/{category}', [AdminController::class, 'updateCategory'])->name('categories.update');
    Route::delete('/categories/{category}', [AdminController::class, 'deleteCategory'])->name('categories.destroy');
});

// Moderator Routes
Route::middleware(['auth', PreventBackHistory::class, 'role:moderator'])->prefix('moderator')->name('moderator.')->group(function () {
    Route::get('/dashboard', [ModeratorController::class, 'dashboard'])->name('dashboard');
    Route::get('/jobs', [ModeratorController::class, 'jobs'])->name('jobs');
    Route::put('/jobs/{job}/status', [ModeratorController::class, 'updateJobStatus'])->name('jobs.status');
    Route::post('/jobs/{job}/approve', [ModeratorController::class, 'approveJob'])->name('jobs.approve');
    Route::post('/jobs/{job}/reject', [ModeratorController::class, 'rejectJob'])->name('jobs.reject');
});
