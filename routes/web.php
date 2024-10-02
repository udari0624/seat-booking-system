<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\PDFController;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade as Pdf;
use Illuminate\Support\Facades\Log;

// Redirect root URL to the home route
Route::get('/', function () {
    return redirect()->route('home'); // Redirect to the named route 'home'
});

// Existing routes...
Route::get('/loginRoute', function () {
    return view('user');
})->name('login.view');

Route::get('/admin-login', [AdminController::class, 'showLoginForm'])->name('admin-login');
Route::post('/admin-login', [AdminController::class, 'login']);
Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

Route::get('/admin-dashboard', [AdminController::class, 'show'])->name('admin.show');
Route::post('/admin', [AdminController::class, 'store'])->name('admin.store');

Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
Route::post('/attendance/mark', [AttendanceController::class, 'mark'])->name('attendance.mark');

Route::post('/admin/download-excel', [AdminController::class, 'downloadExcel'])->name('admin.download.excel');

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::get('/seat-booking', [BookingController::class, 'index'])->name('seat.booking');
Route::get('/user-bookings', [BookingController::class, 'userBookings'])->name('user.bookings');

Route::get('/get-seats', [BookingController::class, 'getSeats'])->name('get.seats');
Route::post('/book-seat', [BookingController::class, 'bookSeat'])->name('book.seat');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/homeRoute', function () {
    return view('home'); // Ensure you have home.blade.php created
})->name('home');

Route::get('/changepwRoute', function () {
    return view('changepw');
})->name('changepw');

Route::delete('/cancel-booking/{id}', [BookingController::class, 'cancelBooking'])->name('cancelBooking');

use App\Http\Controllers\PasswordController;

Route::get('/forgot-password', [PasswordController::class, 'showForgotPasswordForm'])->name('changepw');
Route::post('/forgot-password', [PasswordController::class, 'resetPassword'])->name('changepw.reset');






