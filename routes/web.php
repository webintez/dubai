<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;

// Public Homepage & Meeting Booking
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/book-meeting', [HomeController::class, 'bookMeeting'])->name('meeting.book');

// Legacy compatibility
Route::post('/register', [HomeController::class, 'register'])->name('register.submit.legacy');
Route::post('/upload-screenshot', [HomeController::class, 'uploadScreenshot'])->name('register.screenshot');

// User Authentication (Free Registration)
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// User Protected Dashboard (My Bookings)
Route::middleware('auth')->group(function () {
    Route::get('/my-bookings', [AuthController::class, 'myBookings'])->name('user.bookings');
});

// Admin Auth Routes
Route::get('/admin/login', [AdminController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

// Protected Admin Routes
Route::middleware('admin.auth')->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::post('/admin/settings', [AdminController::class, 'updateSettings'])->name('admin.settings.update');

    // Meetings Management
    Route::post('/admin/meetings', [AdminController::class, 'storeMeeting'])->name('admin.meetings.store');
    Route::post('/admin/meetings/{id}', [AdminController::class, 'updateMeeting'])->name('admin.meetings.update');
    Route::post('/admin/meetings/{id}/toggle-status', [AdminController::class, 'toggleMeetingStatus'])->name('admin.meetings.toggle-status');
    Route::delete('/admin/meetings/{id}', [AdminController::class, 'deleteMeeting'])->name('admin.meetings.delete');

    // Bookings & Approvals
    Route::post('/admin/bookings/{id}/approve', [AdminController::class, 'approveBooking'])->name('admin.bookings.approve');
    Route::post('/admin/bookings/{id}/reject', [AdminController::class, 'rejectBooking'])->name('admin.bookings.reject');
    Route::delete('/admin/bookings/{id}', [AdminController::class, 'deleteBooking'])->name('admin.bookings.delete');
});
