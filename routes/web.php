<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DriverController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Middleware\RedirectAdminMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\RatingReplyController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\DriverRatingController;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Route::get('/', function () {
//     return view('welcome');
// })->name('home');
Route::get('/', function () {
    $orderToRate = Auth::check() ? Order::where('user_id', Auth::id())
        ->where('status', Order::STATUS_DELIVERED)
        ->whereDoesntHave('driverRating')
        ->with('driver')
        ->first() : null;

    return view('welcome', compact('orderToRate'));
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
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // *** EXPORT ROUTES ***
    Route::get('products/export', [AdminProductController::class, 'export'])->name('products.export');
    Route::get('categories/export', [CategoryController::class, 'export'])->name('categories.export');
    Route::get('drivers/export', [DriverController::class, 'export'])->name('drivers.export');
    Route::get('orders/export', [OrderController::class, 'export'])->name('orders.export');
    // Resource routes export routes
    Route::resource('categories', CategoryController::class);
    Route::resource('products', AdminProductController::class);
    Route::resource('orders', OrderController::class);
    Route::resource('users', UserController::class);
    Route::resource('drivers', DriverController::class);

    // Order routes - Status và delivery
    Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::patch('orders/{order}/delivery-status', [OrderController::class, 'updateDeliveryStatus'])->name('orders.update-delivery-status');

    // Order - Driver Assignment
    Route::post('orders/{order}/assign-driver', [OrderController::class, 'assignDriver'])->name('orders.assign-driver');
    Route::delete('orders/{order}/unassign-driver', [OrderController::class, 'unassignDriver'])->name('orders.unassign-driver');
    Route::get('orders/{order}/suggested-drivers', [OrderController::class, 'getSuggestedDrivers'])->name('orders.suggested-drivers');

    // Order - Bulk Actions
    Route::post('orders/bulk-action', [OrderController::class, 'bulkAction'])->name('orders.bulk-action');
    Route::post('orders/auto-assign-drivers', [OrderController::class, 'autoAssignDrivers'])->name('orders.auto-assign-drivers');
    Route::get('orders/export', [OrderController::class, 'export'])->name('orders.export');
    Route::get('orders/{order}/invoice', [OrderController::class, 'printInvoice'])->name('orders.invoice');

    // Product routes
    Route::post('products/{product}/update-stock', [AdminProductController::class, 'updateStock'])->name('products.update-stock');
    Route::post('products/{product}/toggle-active', [AdminProductController::class, 'toggleActive'])->name('products.toggle-active');
    Route::post('products/{product}/toggle-featured', [AdminProductController::class, 'toggleFeatured'])->name('products.toggle-featured');
    Route::post('products/bulk-action', [AdminProductController::class, 'bulkAction'])->name('products.bulk-action');
    Route::get('products/export', [AdminProductController::class, 'export'])->name('products.export');

    // User routes
    Route::post('users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggle-active');
    Route::post('users/{user}/change-role', [UserController::class, 'changeRole'])->name('users.change-role');

    // Driver routes
    Route::post('drivers/{driver}/toggle-status', [DriverController::class, 'toggleStatus'])->name('drivers.toggle-status');
    Route::post('drivers/{driver}/assign-order', [DriverController::class, 'assignToOrder'])->name('drivers.assign-order');
    Route::get('drivers/available', [DriverController::class, 'getAvailableDrivers'])->name('drivers.available');
    Route::post('drivers/bulk-action', [DriverController::class, 'bulkAction'])->name('drivers.bulk-action');
    Route::get('drivers/export', [DriverController::class, 'export'])->name('drivers.export');
    Route::get('drivers/performance', [DriverController::class, 'performanceReport'])->name('drivers.performance');

    // Dashboard API routes
    Route::get('/dashboard/revenue-data', [DashboardController::class, 'revenueData'])->name('dashboard.revenue-data');
    Route::get('/dashboard/top-products', [DashboardController::class, 'topProducts'])->name('dashboard.top-products');
    Route::get('/dashboard/top-customers', [DashboardController::class, 'topCustomers'])->name('dashboard.top-customers');
    Route::get('/dashboard/order-status-data', [DashboardController::class, 'getOrderStatusData'])->name('dashboard.order-status-data');
    Route::get('/dashboard/driver-performance', [DashboardController::class, 'getDriverPerformance'])->name('dashboard.driver-performance');
});

// Driver Rating Routes
Route::post('/driver-ratings', [DriverRatingController::class, 'store'])->name('driver-ratings.store');
Route::get('/skip-driver-rating', [DriverRatingController::class, 'skipDriverRating'])->name('skip.driver.rating');

// Chatbot Route
Route::post('/chatbot/chat', [ChatbotController::class, 'chat'])->name('chatbot.chat');

// Cong thanh toan
Route::post('/vnpay_payment', [CheckoutController::class, 'vnpay_payment'])->name('vnpay.payment');
Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');

require __DIR__ . '/auth.php';
