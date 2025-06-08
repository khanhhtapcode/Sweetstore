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
use Illuminate\Http\Request;

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
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

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

// Chatbot Route
Route::post('/chatbot/chat', [ChatbotController::class, 'chat'])->name('chatbot.chat');

// Email Verification Routes - OVERRIDE TRƯỚC AUTH.PHP
Route::middleware('auth')->group(function () {
    Route::get('/verify-email', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::post('/email/verification-notification', function (Request $request) {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('dashboard');
        }

        $request->user()->sendEmailVerificationNotification();
        return back()->with('status', 'verification-link-sent');
    })->middleware(['throttle:6,1'])->name('verification.send');
});

// Email Verification - Public route (không cần auth)
Route::get('/verify-email/{id}/{hash}', function (Request $request, $id, $hash) {
    try {
        \Log::info('Email verification attempt', [
            'id' => $id,
            'hash' => $hash,
            'url' => $request->fullUrl()
        ]);

        $user = \App\Models\User::find($id);

        if (!$user) {
            \Log::error('User not found: ' . $id);
            return redirect()->route('login')->with('error', 'Người dùng không tồn tại!');
        }

        if ($user->hasVerifiedEmail()) {
            \Log::info('User already verified');
            \Auth::login($user);

            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard')->with('verified', 'Email đã được xác thực!');
            }
            return redirect()->route('dashboard')->with('verified', 'Email đã được xác thực!');
        }

        $expectedHash = sha1($user->getEmailForVerification());
        if ($expectedHash !== $hash) {
            \Log::warning('Hash mismatch');
            return redirect()->route('login')->with('error', 'Link xác thực không hợp lệ!');
        }

        $user->markEmailAsVerified();
        \Auth::login($user);
        $user->updateLastLogin();

        \Log::info('Email verified successfully for user: ' . $user->id);

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard')->with('verified', 'Email đã được xác thực thành công! 🎉');
        }

        return redirect()->route('dashboard')->with('verified', 'Email đã được xác thực thành công! 🎉');

    } catch (\Exception $e) {
        \Log::error('Email verification error: ' . $e->getMessage());
        return redirect()->route('login')->with('error', 'Có lỗi xảy ra khi xác thực email!');
    }
})->middleware(['throttle:6,1'])->name('verification.verify');

require __DIR__ . '/auth.php';
