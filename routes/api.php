<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//Categories
Route::group(['prefix' => 'categories'], function () {
  Route::get('list', [CategoryController::class, 'getCategories'])->name('pages-category-list');
  Route::post('create', [CategoryController::class, 'create'])->name('category.create');
  Route::delete('delete/{category}', [CategoryController::class, 'destroy'])->name('category.delete');
  Route::get('category-title', [CategoryController::class, 'getCategoriesTitle'])->name('category.title');
});

Route::group(['prefix' => 'products'], function () {
  Route::get('list', [ProductController::class, 'getProductList'])->name('pages-category-list');
  Route::post('create', [ProductController::class, 'create'])->name('categories.create');
  Route::delete('delete/{product}', [ProductController::class, 'destroy'])->name('product.delete');
  Route::post('product-image-upload', [ProductController::class, 'productImageUpload'])->name('product.image.upload');
  Route::post('product-image-delete', [ProductController::class, 'productImageDelete'])->name('product.image.delete');
});

