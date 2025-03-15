<?php

use App\Http\Controllers\API\CustomerAuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerAddressController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
  return $request->user();
});

//Categories
Route::group(['prefix' => 'categories'], function () {
  Route::get('list', [CategoryController::class, 'getCategories'])->name('api-category-list');
  Route::post('create', [CategoryController::class, 'create'])->name('category.create');
  Route::delete('delete/{category}', [CategoryController::class, 'destroy'])->name('category.delete');
  Route::get('category-title', [CategoryController::class, 'getCategoriesTitle'])->name('category.title');
});

Route::group(['prefix' => 'products'], function () {
  Route::get('admin-list/{admin}', [ProductController::class, 'getProductList'])->name('api-product-list-admin');
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


Route::group(['prefix' => 'cart', 'middleware' => 'auth:sanctum'], function () {
  Route::get('count', [CartController::class, 'getCartDetails'])->name('cart.count');
  Route::post('add', [CartController::class, 'addToCart'])->name('cart.add');
  Route::get('/', [CartController::class, 'getCartList'])->name('cart.list');
  Route::put('update-quantity', [CartController::class, 'updateCartQuantity'])->name('cart.update');
  Route::delete('remove', [CartController::class, 'removeProductFromCart'])->name('cart.remove');
});

Route::group(['prefix' => 'customer', 'middleware' => 'auth:sanctum'], function () {
  Route::post('logout', [CustomerAuthController::class, 'logout'])->name('customer.logout');
  Route::put('update', [CustomerController::class, 'update'])->name('customer.update');
  Route::get('profile', [CustomerAuthController::class, 'profile'])->name('customer.profile');

  Route::group(['prefix' => 'address'], function () {
    Route::post('store', [CustomerAddressController::class, 'storeAddress'])->name('customer.address.store');
    Route::get('list', [CustomerAddressController::class, 'getAddressList'])->name('customer.address.list');
    Route::get('show/{address}', [CustomerAddressController::class, 'showAddress'])->name('customer.address.show');
    Route::put('update/{address}', [CustomerAddressController::class, 'updateAddress'])->name('customer.address.update');
    Route::delete('delete/{address}', [CustomerAddressController::class, 'deleteAddress'])->name('customer.address.delete');
  });
});

Route::group(['prefix' => 'checkout', 'middleware' => 'auth:sanctum'], function () {
  Route::post('store', [OrderController::class, 'store'])->name('order.store');
  Route::get('list', [OrderController::class, 'index'])->name('order.list');
  Route::get('show/{order}', [OrderController::class, 'show'])->name('order.show');
  Route::put('update/{order}', [OrderController::class, 'update'])->name('order.update');
  Route::delete('delete/{order}', [OrderController::class, 'destroy'])->name('order.delete');
});

Route::prefix('customer')->group(function () {


  Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [CustomerAuthController::class, 'logout']);
    Route::get('/profile', [CustomerAuthController::class, 'profile']);
  });
});



Route::post('/register', [CustomerAuthController::class, 'register']);
Route::post('/login', [CustomerAuthController::class, 'login']);
