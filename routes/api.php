<?php

use App\Http\Controllers\API\CustomerAuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerAddressController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\FcmTokenController;
use App\Http\Controllers\OneSignalController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
  return $request->user();
});

//Categories
Route::group(['prefix' => 'categories'], function () {
  Route::get('list', [CategoryController::class, 'getCategories'])->name('api-category-list');
  Route::post('create', [CategoryController::class, 'create'])->name('category.create');
  Route::get('category-details/{category}', [CategoryController::class, 'edit'])->name('category.edit');
  Route::delete('delete/{category}', [CategoryController::class, 'destroy'])->name('category.delete');
  Route::post('restore/{category}', [CategoryController::class, 'restore'])->name('category.restore');
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
  Route::post('restore/{product}', [ProductController::class, 'restore'])->name('product.restore');
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
    Route::post('', [CustomerAddressController::class, 'storeAddress'])->name('customer.address.store');
    Route::get('list', [CustomerAddressController::class, 'getAddressList'])->name('customer.address.list');
    Route::get('show/{address}', [CustomerAddressController::class, 'showAddress'])->name('customer.address.show');
    Route::put('update/{address}', [CustomerAddressController::class, 'updateAddress'])->name('customer.address.update');
    Route::delete('delete/{address}', [CustomerAddressController::class, 'deleteAddress'])->name('customer.address.delete');
  });
});

Route::group(['prefix' => 'admin'], function () {
  Route::get('customer-list', [CustomerController::class, 'customerList'])->name('customer.list');
  Route::get('customer-details/{customer}', [CustomerController::class, 'show'])->name('customer.details');
  Route::put('customer-update/{customer}', [CustomerController::class, 'update'])->name('customer.update');
  Route::delete('customer-delete/{customer}', [CustomerController::class, 'destroy'])->name('customer.delete');
  Route::get('customer-order-list/{customer}', [CustomerController::class, 'customerOrders'])->name('order.list');
  Route::get('payment-list', [PaymentController::class, 'getPaymentList'])->name('payment.list');
});

Route::group(['prefix' => 'checkout', 'middleware' => 'auth:sanctum'], function () {
  Route::post('store', [OrderController::class, 'store'])->name('order.store');
  Route::get('list', [OrderController::class, 'index'])->name('order.list');
  Route::get('show/{order}', [OrderController::class, 'show'])->name('order.show');
  Route::put('update/{order}', [OrderController::class, 'update'])->name('order.update');
  Route::delete('delete/{order}', [OrderController::class, 'destroy'])->name('order.delete');
});

Route::group(['prefix'=> 'order'], function () {
  Route::get('list', [OrderController::class, 'getOrderList'])->name('order.list');
  Route::get('details/{order}', [OrderItemController::class, 'getOrderDetails'])->name('order.details');
  Route::put('update-status/{order}', [OrderController::class, 'updateOrderStatus'])->name('order.update-status');
});

Route::prefix('customer')->group(function () {


  Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [CustomerAuthController::class, 'logout']);
    Route::get('/profile', [CustomerAuthController::class, 'profile']);
  });
});



Route::post('/register', [CustomerAuthController::class, 'register']);
Route::post('/login', [CustomerAuthController::class, 'login']);

// FCM Token routes
Route::group(['prefix' => 'fcm'], function () {
  Route::post('save', [FcmTokenController::class, 'saveFCM'])->name('fcm.save');
  Route::get('user/{userId}', [FcmTokenController::class, 'getUserTokens'])->name('fcm.user-tokens');
  Route::put('deactivate/{tokenId}', [FcmTokenController::class, 'deactivateToken'])->name('fcm.deactivate');
});

// OneSignal Push Notification routes
Route::group(['prefix' => 'onesignal'], function () {
  Route::get('test', [OneSignalController::class, 'test'])->name('onesignal.test');
  Route::get('app-info', [OneSignalController::class, 'getAppInfo'])->name('onesignal.app-info');
  Route::get('notification/{notificationId}', [OneSignalController::class, 'getNotification'])->name('onesignal.get-notification');
  
  Route::post('send/all', [OneSignalController::class, 'sendToAll'])->name('onesignal.send-all');
  Route::post('send/segments', [OneSignalController::class, 'sendToSegments'])->name('onesignal.send-segments');
  Route::post('send/player-ids', [OneSignalController::class, 'sendToPlayerIds'])->name('onesignal.send-player-ids');
  Route::post('send/external-user-ids', [OneSignalController::class, 'sendToExternalUserIds'])->name('onesignal.send-external-users');
  Route::post('send/alias-external-ids', [OneSignalController::class, 'sendToAliasExternalIds'])->name('onesignal.send-alias-external-ids');
});

Route::get('/test', function () {
    Log::info('API test endpoint hit');
    return response()->json(['message' => 'API is working!']);
})->name('api.test');

