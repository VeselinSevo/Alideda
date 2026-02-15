<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\ProductImageController;
use App\Http\Controllers\ContactController;


use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Owner\StoreOrderController;


use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CountryController as AdminCountryController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\StoreController as AdminStoreController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use \App\Http\Controllers\Admin\ContactMessageController as AdminContactMessageController;
use \App\Http\Controllers\Admin\ActivityLogController as AdminActivityLogController;



Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'notbanned', 'admin'])
    ->group(function () {

        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Countries CRUD
        Route::resource('countries', AdminCountryController::class);


        //Logs
    
        Route::get('activity-logs', [\App\Http\Controllers\Admin\ActivityLogController::class, 'index'])
            ->name('activity-logs.index');
        //Messages 
        Route::get('messages', [AdminContactMessageController::class, 'index'])->name('messages.index');
        Route::get('messages/{message}', [AdminContactMessageController::class, 'show'])->name('messages.show');
        Route::delete('messages/{message}', [AdminContactMessageController::class, 'destroy'])->name('messages.destroy');
        // Users (ban/unban/delete)
        Route::get('users', [AdminUserController::class, 'index'])->name('users.index');
        Route::post('users/{user}/ban', [AdminUserController::class, 'ban'])->name('users.ban');
        Route::post('users/{user}/unban', [AdminUserController::class, 'unban'])->name('users.unban');
        Route::delete('users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

        // Stores & Products (admin delete)
        Route::get('stores', [AdminStoreController::class, 'index'])->name('stores.index');
        Route::patch('stores/{store}/verify', [AdminStoreController::class, 'toggleVerify'])
            ->name('stores.verify');
        Route::delete('stores/{store}', [AdminStoreController::class, 'destroy'])->name('stores.destroy');

        // Orders (view + update status)
    
        Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::patch('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');

        Route::get('products', [AdminProductController::class, 'index'])->name('products.index');
        Route::delete('products/{product}', [AdminProductController::class, 'destroy'])->name('products.destroy');
    });


Route::get('/', function () {
    return redirect()->route('stores.index');
});

//STORES ROUTES

// Logged in: create store (MUST be before /stores/{store})
Route::middleware('auth')->group(function () {
    Route::resource('stores', StoreController::class)->only(['create', 'store']);
});


// Logged in + owns a store: edit/update/delete store
Route::middleware(['auth', 'notbanned', 'store.owner'])->group(function () {
    Route::resource('stores', StoreController::class)->only([
        'edit',
        'update',
        'destroy'
    ]);
    Route::patch('product-images/{image}/primary', [ProductImageController::class, 'makePrimary'])
        ->name('product-images.primary');

    Route::post('products/{product}/images', [ProductImageController::class, 'store'])
        ->name('products.images.store');

    Route::delete('product-images/{image}', [ProductImageController::class, 'destroy'])
        ->name('product-images.destroy');
});

// My stores: list stores owned by logged in user
Route::middleware(['auth', 'notbanned'])->group(function () {
    // cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/update/{product}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{product}', [CartController::class, 'remove'])->name('cart.remove');

    // checkout
    Route::get('/checkout', [CheckoutController::class, 'create'])->name('checkout.create');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    // orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/my-stores', [StoreController::class, 'myStores'])->name('stores.mine');
});




//PRODUCTS ROUTES

// Logged in + store owner: can manage products
Route::middleware(['auth', 'notbanned', 'store.owner'])->group(function () {
    Route::resource('products', ProductController::class)->only([
        'create',
        'store',
        'edit',
        'update',
        'destroy'
    ]);
});
// Public: browse stores
Route::resource('stores', StoreController::class)->only(['index', 'show']);


// Public: anyone can browse products
Route::resource('products', ProductController::class)->only(['index', 'show']);

Route::get('/contact', [ContactController::class, 'create'])->name('contact.create');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');


Route::view('/about-author', 'about-author')->name('about.author');







// Breeze routes
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'notbanned', 'verified'])->name('dashboard');

Route::middleware(['auth', 'notbanned'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware(['auth', 'notbanned', 'store.owner'])->group(function () {
    Route::get('/store-orders', [StoreOrderController::class, 'index'])->name('owner.orders.index');
    Route::get('/store-orders/{storeOrder}', [StoreOrderController::class, 'show'])->name('owner.orders.show');
    Route::patch('/store-orders/{storeOrder}/status', [StoreOrderController::class, 'updateStatus'])->name('owner.orders.status');
});


require __DIR__ . '/auth.php';
