<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Welcome page
Route::get('/', function () {
    return view('welcome');
});

// Guest routes (only accessible when not logged in)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    
    // Forgot Password Routes
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.forgot');
    Route::post('/forgot-password', [AuthController::class, 'sendResetCode'])->name('password.email');
    Route::get('/verify-code', [AuthController::class, 'showVerifyCode'])->name('password.verify.show');
    Route::post('/verify-code', [AuthController::class, 'verifyCode'])->name('password.verify');
    Route::get('/reset-password', [AuthController::class, 'showResetPassword'])->name('password.reset.show');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.reset');
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Project routes
    Route::resource('projects', \App\Http\Controllers\ProjectController::class);
    Route::post('projects/{id}/star', [\App\Http\Controllers\ProjectController::class, 'toggleStar'])->name('projects.star');
    Route::post('projects/{id}/restore', [\App\Http\Controllers\ProjectController::class, 'restore'])->name('projects.restore');
    Route::delete('projects/{id}/force', [\App\Http\Controllers\ProjectController::class, 'forceDelete'])->name('projects.force-delete');
    Route::get('/drafts', [\App\Http\Controllers\ProjectController::class, 'drafts'])->name('projects.drafts');
    Route::get('/trash', [\App\Http\Controllers\ProjectController::class, 'trash'])->name('projects.trash');
    Route::get('/starred', [\App\Http\Controllers\ProjectController::class, 'starred'])->name('projects.starred');
    
    // Project sharing routes
    Route::post('projects/{id}/share', [\App\Http\Controllers\ProjectController::class, 'shareProject'])->name('projects.share');
    Route::delete('projects/{id}/share/{userId}', [\App\Http\Controllers\ProjectController::class, 'removeShare'])->name('projects.share.remove');
    Route::patch('projects/{id}/share/{userId}/permission', [\App\Http\Controllers\ProjectController::class, 'updateSharePermission'])->name('projects.share.permission');
    
    // Bulk actions
    Route::post('/trash/restore-all', [\App\Http\Controllers\ProjectController::class, 'restoreAll'])->name('projects.restore-all');
    Route::delete('/trash/empty', [\App\Http\Controllers\ProjectController::class, 'emptyTrash'])->name('projects.empty-trash');
    
    // Wizard routes for AI-powered empathy mapping
    Route::get('/projects/{id}/empathy-map', [\App\Http\Controllers\ProjectController::class, 'showEmpathyMap'])->name('projects.empathy-map');
    Route::post('/projects/{id}/empathy-map', [\App\Http\Controllers\ProjectController::class, 'storeEmpathyMap'])->name('projects.empathy-map.store');
    Route::get('/projects/{id}/customer-profile', [\App\Http\Controllers\ProjectController::class, 'showCustomerProfile'])->name('projects.customer-profile');
    Route::put('/projects/{id}/customer-profile/item', [\App\Http\Controllers\ProjectController::class, 'updateCustomerProfileItem'])->name('projects.customer-profile.update-item');
    Route::delete('/projects/{id}/customer-profile/item', [\App\Http\Controllers\ProjectController::class, 'deleteCustomerProfileItem'])->name('projects.customer-profile.delete-item');
    Route::post('/projects/{id}/customer-profile/regenerate', [\App\Http\Controllers\ProjectController::class, 'regenerateCustomerProfile'])->name('projects.customer-profile.regenerate');
    Route::post('/projects/{id}/finalize', [\App\Http\Controllers\ProjectController::class, 'finalizeProject'])->name('projects.finalize');
});

// Super Admin Routes
Route::middleware(['auth', 'role:super_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', \App\Http\Controllers\Admin\UserManagementController::class)->except(['show']);
    Route::get('users/{id}/reset-password', [\App\Http\Controllers\Admin\UserManagementController::class, 'resetPassword'])->name('users.reset-password');
    Route::post('users/{id}/update-password', [\App\Http\Controllers\Admin\UserManagementController::class, 'updatePassword'])->name('users.update-password');
});
