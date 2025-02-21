<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//Categories
Route::group(['prefix' => 'categories'], function () {
  Route::get('list', [CategoryController::class, 'getCategories'])->name('api-category-list');
  Route::post('create', [CategoryController::class, 'create'])->name('category.create');
  Route::delete('delete/{category}', [CategoryController::class, 'destroy'])->name('category.delete');
  Route::get('category-title', [CategoryController::class, 'getCategoriesTitle'])->name('category.title');
});

Route::group(['prefix' => 'products'], function () {
  Route::get('list', [ProductController::class, 'getProductList'])->name('api-product-list');
  Route::post('create', [ProductController::class, 'create'])->name('api-categories.create');
  Route::get('details/{product}', [ProductController::class, 'show'])->name('product.show');
  Route::delete('delete/{product}', [ProductController::class, 'destroy'])->name('product.delete');
  Route::post('product-image-upload', [ProductController::class, 'productImageUpload'])->name('product.image.upload');
  Route::post('product-image-delete', [ProductController::class, 'productImageDelete'])->name('product.image.delete');
});

Route::group(['prefix' => 'shop'], function () {
  Route::get('list', [ProductController::class, 'getProductListShop'])->name('shop-product-list');
  Route::get('add', [ProductController::class, 'create'])->name('api-product-add');
  Route::post('store', [ProductController::class, 'store'])->name('api-product-store');
  Route::get('edit/{product}', [ProductController::class, 'edit'])->name('api-product-edit');
  Route::put('update/{product}', [ProductController::class, 'update'])->name('api-product-update');
  Route::delete('delete/{product}', [ProductController::class, 'destroy'])->name('api-product-delete');
});


Route::group(['prefix' => 'cart'], function () {
  Route::post('add', [CartController::class, 'addToCart'])->name('cart.add');
  Route::get('list/{customer_id}', [CartController::class, 'getCartList'])->name('cart.list');
});
