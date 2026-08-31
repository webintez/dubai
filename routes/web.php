<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;

// Frontend Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/register', [HomeController::class, 'register'])->name('register.submit');
Route::post('/upload-screenshot', [HomeController::class, 'uploadScreenshot'])->name('register.screenshot');

// Admin Auth Routes
Route::get('/admin/login', [AdminController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

// Protected Admin Routes
Route::middleware('admin.auth')->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::post('/admin/settings', [AdminController::class, 'updateSettings'])->name('admin.settings.update');
    Route::post('/admin/submissions/{id}/status', [AdminController::class, 'updateStatus'])->name('admin.submissions.status');
    Route::delete('/admin/submissions/{id}', [AdminController::class, 'deleteSubmission'])->name('admin.submissions.delete');
});
