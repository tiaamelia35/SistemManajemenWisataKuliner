<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TouristController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\AdminController;

// 1. Guest & Common Routes
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// 2. Tourist / Wisatawan Routes
Route::middleware(['auth', 'role:tourist'])->group(function () {
    Route::get('/tourist/jelajah', [TouristController::class, 'jelajah'])->name('tourist.jelajah');
    Route::get('/tourist/restaurant/{id}', [TouristController::class, 'detail'])->name('tourist.detail');
    Route::get('/tourist/restaurant/{id}/booking', [TouristController::class, 'showBooking'])->name('tourist.booking');
    Route::post('/tourist/restaurant/{id}/booking', [TouristController::class, 'storeBooking'])->name('tourist.booking.post');
    Route::get('/tourist/bookings', [TouristController::class, 'bookings'])->name('tourist.bookings');
    Route::post('/tourist/restaurant/{id}/review', [TouristController::class, 'storeReview'])->name('tourist.review.post');
    Route::get('/tourist/profile', [TouristController::class, 'profile'])->name('tourist.profile');
    Route::post('/tourist/profile', [TouristController::class, 'updateProfile'])->name('tourist.profile.post');
});

// 3. Restaurant Owner / Pemilik Routes
Route::middleware(['auth', 'role:owner'])->group(function () {
    Route::get('/owner/dashboard', [OwnerController::class, 'dashboard'])->name('owner.dashboard');
    Route::get('/owner/menus', [OwnerController::class, 'showMenus'])->name('owner.menus');
    Route::post('/owner/menus', [OwnerController::class, 'storeMenu'])->name('owner.menus.post');
    Route::post('/owner/menus/{id}/delete', [OwnerController::class, 'deleteMenu'])->name('owner.menus.delete');
    Route::post('/owner/bookings/{id}/status', [OwnerController::class, 'updateBookingStatus'])->name('owner.bookings.status');
    Route::post('/owner/restaurant/update', [OwnerController::class, 'updateRestaurant'])->name('owner.restaurant.update');
});

// 4. System Admin Routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
    Route::post('/admin/users/{id}/toggle', [AdminController::class, 'toggleUserStatus'])->name('admin.users.toggle');
    Route::post('/admin/verify/restaurant/{id}', [AdminController::class, 'verifyRestaurant'])->name('admin.verify.restaurant');
    Route::post('/admin/verify/certification/{id}', [AdminController::class, 'verifyCertification'])->name('admin.verify.certification');
    Route::post('/admin/reviews/{id}/status', [AdminController::class, 'updateReviewStatus'])->name('admin.reviews.status');
    Route::get('/admin/reports', [AdminController::class, 'reports'])->name('admin.reports');
});
