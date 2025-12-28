<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\HomeController;


Route::get('/', [HomeController::class, 'index'])->name('home');

// ========== ADMIN ROUTES ==========

Route::middleware(['auth', AdminMiddleware::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        // Admin product CRUD
        Route::resource('products', ProductController::class)->names([
            'index' => 'products.index',
            'create' => 'products.create',
            'store' => 'products.store',
            'edit' => 'products.edit',
            'update' => 'products.update',
            'destroy' => 'products.destroy',
            'show' => 'products.show'
        ]);

        Route::patch('products/{product}/toggle-availability', [ProductController::class, 'toggleAvailability'])
            ->name('admin.products.toggle-availability');

        // ========== ADD ITEMS ROUTES HERE ==========
        // Admin items CRUD
        Route::resource('items', \App\Http\Controllers\Admin\ItemController::class)->names([
            'index' => 'items.index',
            'create' => 'items.create',
            'store' => 'items.store',
            'edit' => 'items.edit',
            'update' => 'items.update',
            'destroy' => 'items.destroy',
        ]);
    });

// Shop Routes
Route::get('/collections', [ShopController::class, 'allCollections'])->name('collections');
Route::get('/collections/women', [ShopController::class, 'women'])->name('collections.women');
Route::get('/collections/men', [ShopController::class, 'men'])->name('collections.men');
Route::get('/collections/unisex', [ShopController::class, 'unisex'])->name('collections.unisex');
Route::get('/product/{id}', [ShopController::class, 'show'])
    ->name('product.show')
    ->where('id', '[0-9]+');

Route::get('/collections/gifts', [ShopController::class, 'gifts'])->name('collections.gifts');
// Search Routes
Route::get('/search', [ShopController::class, 'search'])->name('search');
Route::get('/search/suggestions', [ShopController::class, 'searchSuggestions'])->name('search.suggestions');



require __DIR__.'/auth.php';

















