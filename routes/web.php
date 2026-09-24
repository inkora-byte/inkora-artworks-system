<?php

declare(strict_types=1);

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\Catalog\DesignController;
use App\Http\Controllers\Catalog\ProductTypeController;
use App\Http\Controllers\Catalog\ProductController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\Inventory\StockAdjustmentController;
use App\Http\Controllers\Inventory\StockMovementController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::resource('users', UserController::class)->except(['show', 'destroy']);
    Route::post('users/{user}/toggle-active', [UserController::class, 'toggleActive'])
        ->name('users.toggle-active');

    Route::get('roles', [RoleController::class, 'index'])->name('roles.index');

    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('settings/{setting}', [SettingController::class, 'update'])->name('settings.update');
});

Route::middleware('auth')->prefix('catalog')->name('catalog.')->group(function () {
    Route::resource('product-types', ProductTypeController::class)->except(['show']);
    Route::resource('designs', DesignController::class)->except(['show']);
    Route::resource('products', ProductController::class)->except(['show']);
    Route::post('products/{product}/toggle-status', [ProductController::class, 'toggleStatus'])
    ->name('products.toggle-status');
});

Route::middleware('auth')->prefix('inventory')->name('inventory.')->group(function () {
    Route::get('stock', [StockAdjustmentController::class, 'index'])->name('stock.index');
    Route::get('stock/{productVariant}/adjust', [StockAdjustmentController::class, 'create'])
        ->name('adjustments.create');
    Route::post('adjustments', [StockAdjustmentController::class, 'store'])->name('adjustments.store');
    Route::get('movements', [StockMovementController::class, 'index'])->name('movements.index');
});

Route::middleware('auth')->get('media/{media}', [MediaController::class, 'show'])
    ->name('media.show');

Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
Route::put('settings/{setting}', [SettingController::class, 'update'])->name('settings.update');

require __DIR__.'/auth.php';
