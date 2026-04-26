<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReleaseFormController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\PublicPortalController;
use Illuminate\Support\Facades\Route;

// Auth
Route::get('/admin',  [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/admin', [AuthController::class, 'login'])->middleware('guest');
Route::post('/logout',[AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Public Portal
Route::get('/', [PublicPortalController::class, 'index'])->name('public.index');
Route::get('/public-search', [PublicPortalController::class, 'search'])->name('public.search');

// Authenticated routes
Route::middleware(['auth', 'role'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Vehicles (Officer + Admin)
    Route::middleware('role:admin,officer')->group(function () {
        Route::get('/vehicles',              [VehicleController::class, 'index'])->name('vehicles.index');
        Route::get('/vehicles/create',       [VehicleController::class, 'create'])->name('vehicles.create');
        Route::post('/vehicles',             [VehicleController::class, 'store'])->name('vehicles.store');
        Route::get('/vehicles/{vehicle}',    [VehicleController::class, 'show'])->name('vehicles.show');
        Route::get('/vehicles/{vehicle}/edit',   [VehicleController::class, 'edit'])->name('vehicles.edit');
        Route::put('/vehicles/{vehicle}',        [VehicleController::class, 'update'])->name('vehicles.update');
        Route::patch('/vehicles/{vehicle}/status',[VehicleController::class, 'updateStatus'])->name('vehicles.update-status');
    });

    // Release Forms
    Route::get('/vehicles/{vehicle}/release-form',     [ReleaseFormController::class, 'show'])->name('release-form.show');
    Route::post('/vehicles/{vehicle}/release-form',    [ReleaseFormController::class, 'generate'])->name('release-form.generate')->middleware('role:admin,officer');

    // Payments (Finance + Admin)
    Route::middleware('role:admin,finance_officer')->group(function () {
        Route::get('/vehicles/{vehicle}/payment',   [PaymentController::class, 'create'])->name('payments.create');
        Route::post('/vehicles/{vehicle}/payment',  [PaymentController::class, 'store'])->name('payments.store');
        Route::get('/payments/{payment}/receipt',   [PaymentController::class, 'receipt'])->name('payments.receipt');
    });

    // Reports (All roles)
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/',        [ReportController::class, 'index'])->name('index');
        Route::get('/daily',   [ReportController::class, 'daily'])->name('daily');
        Route::get('/monthly', [ReportController::class, 'monthly'])->name('monthly');
        Route::get('/revenue', [ReportController::class, 'revenue'])->name('revenue');
    });

    // Audit log (Admin only)
    Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit.index')->middleware('role:admin');

    // User management (Admin only)
    Route::middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
    });
});
