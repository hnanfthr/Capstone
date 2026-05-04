<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\StockDashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\PayrollController;

Route::get('/', function () {
    return view('welcome');
});

// Auth Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Dashboard Routes
Route::middleware('auth')->group(function () {
    Route::resource('products', ProductController::class);

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');

    Route::post('/productions', [ProductionController::class, 'store'])->name('productions.store');
    Route::get('/productions/report', [ProductionController::class, 'report'])->name('productions.report');

    Route::get('/stocks/dashboard', [StockDashboardController::class, 'index'])->name('stocks.dashboard');

    Route::resource('employees', EmployeeController::class);
    Route::get('/attendances', [AttendanceController::class, 'index'])->name('attendances.index');
    Route::post('/attendances/clock-in', [AttendanceController::class, 'clockIn'])->name('attendances.clockIn');
    Route::post('/attendances/clock-out', [AttendanceController::class, 'clockOut'])->name('attendances.clockOut');
    
    Route::get('/payroll', [PayrollController::class, 'index'])->name('payroll.index');
});
