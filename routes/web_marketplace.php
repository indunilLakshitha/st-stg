<?php

use App\Http\Controllers\Marketplace\Admin\CategoryController;
use App\Http\Controllers\Marketplace\Admin\IndexController as AdminIndexController;
use App\Http\Controllers\Marketplace\Admin\ProductController;
use App\Http\Controllers\Marketplace\Admin\SubCategoryController;
use App\Http\Controllers\Marketplace\User\CartController;
use App\Http\Controllers\Marketplace\User\IndexController;
use App\Http\Controllers\Marketplace\User\OrderContoller;
use App\Http\Controllers\Marketplace\User\ProductController as UserProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



Route::prefix('/')->name('marketplace.')->group(function () {
    Route::get('/', [IndexController::class, 'index'])->name('user.index');
    Route::get('/product/{slug}', [UserProductController::class, 'view'])->name('item.view');
    Route::get('filter/', [IndexController::class, 'filter'])->name('item.filter');
});

Route::middleware(['auth:web'])->group(function () {

    // Route::prefix('/')->name('marketplace.')->group(function () {
    //     Route::get('/', [IndexController::class, 'index'])->name('user.index');
    //     Route::get('item/{id}', [UserProductController::class, 'view'])->name('item.view');
    // });

    Route::prefix('/admin')->name('marketplace.admin.')->group(function () {
        Route::get('/', [AdminIndexController::class, 'index'])->name('index');

        Route::prefix('product')->name('product.')->group(function () {
            Route::get('/', [ProductController::class, 'index'])->name('index');
            Route::get('/create', [ProductController::class, 'create'])->name('create');
            Route::get('/edit/{id}', [ProductController::class, 'edit'])->name('edit');
        });

        Route::prefix('category')->name('category.')->group(function () {
            Route::get('/', [CategoryController::class, 'index'])->name('index');
            Route::get('/create', [CategoryController::class, 'create'])->name('create');
            Route::get('/edit/{id}', [CategoryController::class, 'edit'])->name('edit');
        });

        Route::prefix('sub-category')->name('subCategory.')->group(function () {
            Route::get('/', [SubCategoryController::class, 'index'])->name('index');
            Route::get('/create', [SubCategoryController::class, 'create'])->name('create');
            Route::get('/edit/{id}', [SubCategoryController::class, 'edit'])->name('edit');
        });
    });

    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
    });
});
