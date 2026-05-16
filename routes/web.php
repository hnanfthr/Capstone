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
use App\Http\Controllers\StorefrontController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;

// Storefront Routes
Route::get('/', [StorefrontController::class, 'index'])->name('storefront.index');
Route::get('/catalog', [StorefrontController::class, 'catalog'])->name('storefront.catalog');
Route::get('/product/{product}', [StorefrontController::class, 'show'])->name('storefront.show');
Route::post('/reviews', [\App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');

    // Web Cart and Checkout have been deprecated in favor of WA Pre-Order

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
    Route::get('/productions/{production}/print', [ProductionController::class, 'print'])->name('productions.print');

    Route::get('/stocks/dashboard', [StockDashboardController::class, 'index'])->name('stocks.dashboard');
    Route::post('/stocks/adjust', [StockDashboardController::class, 'adjust'])->name('stocks.adjust');

    Route::resource('employees', EmployeeController::class);
    Route::get('/attendances', [AttendanceController::class, 'index'])->name('attendances.index');
    Route::post('/attendances/clock-in', [AttendanceController::class, 'clockIn'])->name('attendances.clockIn');
    Route::post('/attendances/clock-out', [AttendanceController::class, 'clockOut'])->name('attendances.clockOut');
    
    Route::get('/payroll', [PayrollController::class, 'index'])->name('payroll.index');
    Route::post('/payroll/store', [PayrollController::class, 'store'])->name('payroll.store');
    Route::get('/payroll/history', [PayrollController::class, 'history'])->name('payroll.history');
    Route::get('/payroll/export', [PayrollController::class, 'export'])->name('payroll.export');
    
    // Website Settings & Banners
    Route::get('/settings', [\App\Http\Controllers\SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [\App\Http\Controllers\SettingController::class, 'update'])->name('settings.update');
    Route::resource('banners', \App\Http\Controllers\BannerController::class)->except(['index', 'show']);
});
