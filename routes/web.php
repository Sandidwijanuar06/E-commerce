<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Admin\ShipmentController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\CMSController;
use App\Http\Controllers\Admin\FulfillmentController;
use App\Http\Controllers\Admin\ReturnController;
use App\Http\Controllers\Admin\WarehouseController;
use App\Http\Controllers\Admin\SecurityLogController;
use App\Http\Controllers\Store\HomeController;
use App\Http\Controllers\Store\StoreProductController;
use App\Http\Controllers\Store\CartController;
use App\Http\Controllers\Store\CheckoutController;
use App\Http\Controllers\Store\CustomerDashboardController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// --- PUBLIC ROUTES ---
Route::get('/', [HomeController::class, 'index'])->name('store.index');
Route::get('/products', [StoreProductController::class, 'index'])->name('store.products');
Route::get('/product/{slug}', [StoreProductController::class, 'show'])->name('store.product.detail');

// --- GUEST ONLY (Login & Register) ---
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// --- AUTH REQUIRED ---
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');

    // --- CUSTOMER AREA (Hanya Role: customer) ---
    Route::middleware(['role:customer'])->prefix('customer')->name('customer.')->group(function () {
        Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
        Route::get('/orders', [CustomerDashboardController::class, 'orders'])->name('orders');
        Route::get('/orders/{order_number}', [CustomerDashboardController::class, 'showOrder'])->name('order.show');
        Route::get('/settings', [CustomerDashboardController::class, 'settings'])->name('settings');
    });

    // --- ADMIN AREA (Hanya Role: admin) ---
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('users', UserController::class);
        Route::resource('roles', RoleController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('products', ProductController::class);
        Route::resource('warehouses', WarehouseController::class);
        Route::resource('orders', OrderController::class)->only(['index', 'show']);

        Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
        Route::get('/orders/{order}/print', [OrderController::class, 'printInvoice'])->name('orders.print');

        Route::get('/stock-report', [StockController::class, 'index'])->name('stock.index');
        Route::get('/stock-adjustment', [StockController::class, 'createAdjustment'])->name('stock.create');
        Route::post('/stock-adjustment', [StockController::class, 'adjust'])->name('stock.adjust');

        Route::get('/shipments', [ShipmentController::class, 'index'])->name('shipments.index');
        Route::get('/shipments/{id}', [ShipmentController::class, 'show'])->name('shipments.show');

        Route::resource('marketing/coupons', CouponController::class)->names(['index' => 'marketing.coupons']);
        
        Route::resource('marketing/cms', CMSController::class)->names([
            'index'   => 'marketing.cms.index',
            'store'   => 'marketing.cms.store',
            'update'  => 'marketing.cms.update',
            'destroy' => 'marketing.cms.destroy',
        ]);

        Route::get('logs', [App\Http\Controllers\Admin\SecurityLogController::class, 'index'])->name('security.logs');

        Route::get('/fulfillment/packing', [FulfillmentController::class, 'packingList'])->name('fulfillment.packing');
        Route::patch('/fulfillment/pack/{order}', [FulfillmentController::class, 'markAsPacked'])->name('fulfillment.mark_packed');

        Route::get('/fulfillment/returns', [ReturnController::class, 'index'])->name('fulfillment.returns.index');
        Route::patch('/fulfillment/returns/{id}', [ReturnController::class, 'updateStatus'])->name('fulfillment.returns.update');
    });
});