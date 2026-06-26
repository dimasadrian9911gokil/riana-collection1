<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    HomeController,
    ProductController,
    CartController,
    AddressController,
    OrderController,
    CheckoutController,
    ProfileController,
    AiAnalyzerController
};
use App\Http\Controllers\Auth\{
    AuthenticatedSessionController,
    RegisteredUserController
};

/*
|--------------------------------------------------------------------------
| 1. PUBLIC ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/flash-sale', [HomeController::class, 'flashsale'])->name('flashsale');

// AI Analyzer Routes
Route::get('/ai-analyzer', [AiAnalyzerController::class, 'index'])->name('ai.analyzer');
Route::post('/ai-analyzer/analyze', [AiAnalyzerController::class, 'analyze'])->name('ai.analyze');

// Product Routes (Disesuaikan untuk filter)
Route::get('/products', [ProductController::class, 'index'])->name('products');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// Static Pages
Route::get('/categories', [HomeController::class, 'categories'])->name('categories');
Route::get('/brands', [HomeController::class, 'brands'])->name('brands');

/*
|--------------------------------------------------------------------------
| 2. GUEST ROUTES (Auth)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

/*
|--------------------------------------------------------------------------
| 3. AUTH ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::view('/dashboard', 'dashboard')->name('dashboard');

    // Profile Management
    Route::prefix('profile')->name('profile.')->controller(ProfileController::class)->group(function () {
        Route::get('/edit', 'edit')->name('edit');
        Route::patch('/update', 'update')->name('update');
        Route::post('/update-ajax', 'updateAjax')->name('update.ajax');
        Route::post('/upload-avatar', 'uploadAvatar')->name('upload.avatar');
    });

    // Ubah Kata Sandi
    Route::put('/password', [\App\Http\Controllers\Auth\PasswordController::class, 'update'])->name('password.update');

    // Product Review
    Route::post('/products/{product}/review', [\App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');

    // Realtime Notifications API
    Route::get('/api/notifications/unread', [\App\Http\Controllers\NotificationController::class, 'unread'])->name('notifications.api.unread');
    Route::post('/api/notifications/mark-read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.api.markRead');
    Route::delete('/notifications/{id}', [\App\Http\Controllers\NotificationController::class, 'destroy'])->name('notifications.destroy');

    // Wishlist
    Route::get('/wishlist', [\App\Http\Controllers\WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/toggle', [\App\Http\Controllers\WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::delete('/wishlist/{id}', [\App\Http\Controllers\WishlistController::class, 'destroy'])->name('wishlist.destroy');

    // Cart Management
    Route::prefix('cart')->name('cart.')->controller(CartController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::patch('/{cart}', 'update')->name('update');
        Route::delete('/{cart}', 'destroy')->name('destroy');
    });

    // Address Management
    Route::prefix('address')->name('address.')->controller(AddressController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::get('/{address}/edit', 'edit')->name('edit');
        Route::patch('/{address}', 'update')->name('update');
        Route::delete('/{address}', 'destroy')->name('destroy');
        Route::patch('/{address}/set-primary', 'setPrimary')->name('setPrimary');
    });
    
    // Checkout Process
    Route::get('/checkout', [\App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/buy-now', [\App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout.buyNow');

    // API Orders Realtime Polling
    Route::get('/api/orders/{order}/status', [\App\Http\Controllers\OrderController::class, 'getStatusJson'])->name('orders.statusJson');

    // Order & Transaction Management
    Route::prefix('orders')->name('orders.')->controller(OrderController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/history', 'history')->name('history');
        Route::post('/store', 'store')->name('store'); 
        Route::get('/{id}/pay', 'pay')->name('pay');
        Route::get('/{id}/success', 'success')->name('success');
        Route::post('/{id}/confirm-payment', 'confirmPayment')->name('confirmPayment');
        Route::post('/{id}/change-payment', 'changePaymentMethod')->name('changePaymentMethod');
        Route::get('/{order}', 'show')->name('show'); // Pastikan ini di akhir
    });

    // Utilities
    Route::get('/voucher', function () {
        $vouchers = \App\Models\Voucher::where('is_active', true)->latest()->get();
        $usedVoucherIds = auth()->check() ? \App\Models\Order::where('user_id', auth()->id())->whereNotNull('voucher_id')->pluck('voucher_id')->toArray() : [];
        return view('voucher.index', compact('vouchers', 'usedVoucherIds'));
    })->name('voucher.index');
    Route::get('/notifications', function (\Illuminate\Http\Request $request) {
        $orders = \App\Models\Order::where('user_id', auth()->id())->latest()->get();
        
        $activeOrder = null;
        if ($request->has('track_id')) {
            $activeOrder = $orders->firstWhere('id', $request->track_id);
        } else {
            $activeOrder = $orders->whereNotIn('status', ['selesai', 'dibatalkan'])->first() ?? $orders->first();
        }
            
        $notifications = auth()->user()->notifications()->latest()->paginate(5);
        return view('notifications.index', compact('activeOrder', 'orders', 'notifications'));
    })->name('notifications.index');
    
    // Auth Logout
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| 4. ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Manajemen Kategori & Brand
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class)->except(['create', 'show', 'edit']);
    Route::resource('brands', \App\Http\Controllers\Admin\BrandController::class)->except(['create', 'show', 'edit']);

    // Manajemen Produk & Stok
    Route::get('/products', [\App\Http\Controllers\Admin\ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [\App\Http\Controllers\Admin\ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [\App\Http\Controllers\Admin\ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [\App\Http\Controllers\Admin\ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [\App\Http\Controllers\Admin\ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [\App\Http\Controllers\Admin\ProductController::class, 'destroy'])->name('products.destroy');
    Route::patch('/products/{product}/stock', [\App\Http\Controllers\Admin\ProductController::class, 'updateStock'])->name('products.stock');
    
    // Manajemen Pesanan
    Route::get('/orders', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [\App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.status');
    Route::delete('/orders/{order}', [\App\Http\Controllers\Admin\OrderController::class, 'destroy'])->name('orders.destroy');
    
    Route::get('/vouchers', [\App\Http\Controllers\Admin\VoucherController::class, 'index'])->name('vouchers.index');
    Route::post('/vouchers', [\App\Http\Controllers\Admin\VoucherController::class, 'store'])->name('vouchers.store');
    Route::put('/vouchers/{voucher}', [\App\Http\Controllers\Admin\VoucherController::class, 'update'])->name('vouchers.update');
    Route::patch('/vouchers/{voucher}/toggle', [\App\Http\Controllers\Admin\VoucherController::class, 'toggle'])->name('vouchers.toggle');
    Route::delete('/vouchers/{voucher}', [\App\Http\Controllers\Admin\VoucherController::class, 'destroy'])->name('vouchers.destroy');

    // Manajemen Pelanggan
    Route::get('/customers', [\App\Http\Controllers\Admin\CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/{customer}', [\App\Http\Controllers\Admin\CustomerController::class, 'show'])->name('customers.show');
    Route::patch('/customers/{customer}/toggle', [\App\Http\Controllers\Admin\CustomerController::class, 'toggle'])->name('customers.toggle');
    Route::delete('/customers/{customer}', [\App\Http\Controllers\Admin\CustomerController::class, 'destroy'])->name('customers.destroy');

    // Laporan Penjualan & Backup
    Route::get('/reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/excel', [\App\Http\Controllers\Admin\ReportController::class, 'exportExcel'])->name('reports.excel');
    Route::get('/reports/pdf', [\App\Http\Controllers\Admin\ReportController::class, 'exportPdf'])->name('reports.pdf');
    Route::get('/backup/full', [\App\Http\Controllers\Admin\ReportController::class, 'fullBackup'])->name('backup.full');
    Route::get('/backup/full-pdf', [\App\Http\Controllers\Admin\ReportController::class, 'fullBackupPdf'])->name('backup.full_pdf');

    // Manajemen Flash Sale
    Route::resource('flash_sales', \App\Http\Controllers\Admin\FlashSaleController::class)->except(['create', 'edit']);
    Route::post('/flash_sales/{flash_sale}/items', [\App\Http\Controllers\Admin\FlashSaleController::class, 'addItem'])->name('flash_sales.addItem');
    Route::put('/flash_sales/items/{item}', [\App\Http\Controllers\Admin\FlashSaleController::class, 'updateItem'])->name('flash_sales.updateItem');
    Route::delete('/flash_sales/items/{item}', [\App\Http\Controllers\Admin\FlashSaleController::class, 'removeItem'])->name('flash_sales.removeItem');
});