<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Middleware\RedirectAdminMiddleware;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// User Dashboard - với middleware để redirect admin
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', RedirectAdminMiddleware::class])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Frontend Product Routes (Public)
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/categories/{category}', [ProductController::class, 'category'])->name('categories.show');

// Admin Routes (Restricted to Admin users only)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Admin Dashboard
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Resource routes
    Route::resource('categories', CategoryController::class);
    Route::resource('products', AdminProductController::class);
    Route::resource('orders', OrderController::class);
    Route::resource('users', UserController::class);

    // Additional routes
    Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');

// Product additional routes
    Route::post('products/{product}/update-stock', [AdminProductController::class, 'updateStock'])->name('products.update-stock');
    Route::post('products/{product}/toggle-active', [AdminProductController::class, 'toggleActive'])->name('products.toggle-active');
    Route::post('products/{product}/toggle-featured', [AdminProductController::class, 'toggleFeatured'])->name('products.toggle-featured');
    Route::post('products/bulk-action', [AdminProductController::class, 'bulkAction'])->name('products.bulk-action');
    Route::get('products/export', [AdminProductController::class, 'export'])->name('products.export');

    // User additional routes
    Route::post('users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggle-active');
    Route::post('users/{user}/change-role', [UserController::class, 'changeRole'])->name('users.change-role');
});

require __DIR__.'/auth.php';
