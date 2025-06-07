<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Middleware\RedirectAdminMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\RatingReplyController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\Admin\DashboardController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// User Dashboard
Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', RedirectAdminMiddleware::class])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Frontend Product Routes (Public)
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/categories/{category}', [ProductController::class, 'category'])->name('categories.show');

// Cart Routes
Route::middleware('auth')->group(function () {
    Route::post('/add-to-cart', [CartController::class, 'add_to_cart'])->name('cart.add');
    Route::get('/show-cart', [CartController::class, 'show_cart'])->name('cart.show_cart');
    Route::post('/update-cart', [CartController::class, 'update_cart'])->name('cart.update');
    Route::post('/delete-cart/{productId}', [CartController::class, 'delete_from_cart'])->name('cart.delete');
    Route::get('/cart/overlay', [CartController::class, 'overlay'])->name('cart.overlay');
    Route::post('/update-showcart', [CartController::class, 'update_show_cart'])->name('cart.update_show_cart');
    Route::post('/delete-showcart/{productId}', [CartController::class, 'delete_from_show_cart'])->name('cart.delete_from_show_cart');
});

// Checkout Routes
Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');
});

// Order Routes - Customer
Route::middleware('auth')->group(function () {
    Route::get('/orders', [CheckoutController::class, 'orderHistory'])->name('orders.history');
    Route::get('/orders/{order}', [CheckoutController::class, 'orderDetail'])->name('orders.detail');
    Route::patch('/orders/{order}/cancel', [CheckoutController::class, 'cancelOrder'])->name('orders.cancel');
});

// Rating Routes
Route::post('/ratings', [RatingController::class, 'store'])->name('ratings.store')->middleware('auth');
Route::post('/ratings/{rating}/replies', [RatingReplyController::class, 'store'])->name('ratings.replies.store')->middleware('auth');

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::resource('categories', CategoryController::class);
    Route::resource('products', AdminProductController::class);
    Route::resource('orders', OrderController::class);
    Route::resource('users', UserController::class);

    Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::post('products/{product}/update-stock', [AdminProductController::class, 'updateStock'])->name('products.update-stock');
    Route::post('products/{product}/toggle-active', [AdminProductController::class, 'toggleActive'])->name('products.toggle-active');
    Route::post('products/{product}/toggle-featured', [AdminProductController::class, 'toggleFeatured'])->name('products.toggle-featured');
    Route::post('products/bulk-action', [AdminProductController::class, 'bulkAction'])->name('products.bulk-action');
    Route::get('products/export', [AdminProductController::class, 'export'])->name('products.export');
    Route::post('users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggle-active');
    Route::post('users/{user}/change-role', [UserController::class, 'changeRole'])->name('users.change-role');

    // Các route API mới cho Dashboard
    Route::get('/dashboard/revenue-data', [DashboardController::class, 'revenueData'])->name('dashboard.revenue-data');
    Route::get('/dashboard/top-products', [DashboardController::class, 'topProducts'])->name('dashboard.top-products');
    Route::get('/dashboard/top-customers', [DashboardController::class, 'topCustomers'])->name('dashboard.top-customers');
    Route::get('/dashboard/order-status-data', [DashboardController::class, 'getOrderStatusData'])->name('dashboard.order-status-data');
});

// Chatbot Route
Route::post('/chatbot/chat', [ChatbotController::class, 'chat'])->name('chatbot.chat');

require __DIR__ . '/auth.php';
