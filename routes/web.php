<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\pages\EcommerceCustomerAll;
use App\Http\Controllers\pages\EcommerceCustomerDetailsBilling;
use App\Http\Controllers\pages\EcommerceCustomerDetailsNotifications;
use App\Http\Controllers\pages\EcommerceCustomerDetailsOverview;
use App\Http\Controllers\pages\EcommerceCustomerDetailsSecurity;
use App\Http\Controllers\pages\EcommerceManageReviews;
use App\Http\Controllers\pages\EcommerceOrderDetails;
use App\Http\Controllers\pages\EcommerceOrderList;
use App\Http\Controllers\pages\EcommerceProductAdd;
use App\Http\Controllers\pages\EcommerceProductCategory;
use App\Http\Controllers\pages\EcommerceProductList;
use App\Http\Controllers\pages\EcommerceReferrals;
use App\Http\Controllers\pages\EcommerceSettingsCheckout;
use App\Http\Controllers\pages\EcommerceSettingsDetails;
use App\Http\Controllers\pages\EcommerceSettingsLocations;
use App\Http\Controllers\pages\EcommerceSettingsNotifications;
use App\Http\Controllers\pages\EcommerceSettingsPayments;
use App\Http\Controllers\pages\EcommerceSettingsShipping;
use App\Http\Controllers\pages\Dashboard as DashboardAlias;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Models\Product;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\language\LanguageController;
use App\Http\Controllers\pages\HomePage;
use App\Http\Controllers\pages\Page2;
use App\Http\Controllers\pages\MiscError;
use App\Http\Controllers\authentications\LoginBasic;
use App\Http\Controllers\authentications\RegisterBasic;

// Main Page Route
Route::get('/', [HomePage::class, 'index'])->name('pages-home');
Route::get('/page-2', [Page2::class, 'index'])->name('pages-page-2');

// locale
Route::get('/lang/{locale}', [LanguageController::class, 'swap']);
Route::get('/pages/misc-error', [MiscError::class, 'index'])->name('pages-misc-error');

// authentication
Route::get('/auth/login-basic', [LoginBasic::class, 'index'])->name('auth-login-basic');
Route::get('/auth/register-basic', [RegisterBasic::class, 'index'])->name('auth-register-basic');
Route::get('/dashboard', [DashboardAlias::class, 'index'])->name('pages-dashboard');


//Products
Route::group(['prefix' => 'products'], function () {
  Route::get('list', [ProductController::class, 'index'])->name('pages-product-list');
  Route::get('add', [ProductController::class, 'create'])->name('pages-product-add');
  Route::post('add', [ProductController::class, 'store'])->name('products.add');
  Route::get('edit/{product}', [ProductController::class, 'edit'])->name('pages-product-edit');
  Route::post('edit/{product}', [ProductController::class, 'update'])->name('products.update');
  Route::get('category', [CategoryController::class, 'index'])->name('pages-product-category');

});
//Categories
Route::group(['prefix' => 'category'], function () {
  Route::post('', [CategoryController::class, 'create'])->name('categories.create');
  Route::post('update/{category}', [CategoryController::class, 'update'])->name('categories.edit');
  Route::get('list', [CategoryController::class, 'index'])->name('pages-category-list');
});
//Orders
Route::group(['prefix' => 'orders'], function () {
  Route::get('list', [EcommerceOrderList::class, 'index'])->name('pages-order-list');
  Route::get('details/{order}', [EcommerceOrderDetails::class, 'index'])->name('pages-order-details');
  Route::get('details', function () {
    return redirect()->route('pages-order-list');
  });
});
//Customers
Route::group(['prefix' => 'customers'], function () {
  Route::get('all', [EcommerceCustomerAll::class, 'index'])->name('pages-customer-all');
  Route::get('details/overview/{customer}', [\App\Http\Controllers\CustomerController::class, 'show'])->name('pages-customer-details-overview');
  Route::get('details/security', [EcommerceCustomerDetailsSecurity::class, 'index'])->name('pages-customer-details-security');
  Route::get('details/billing', [EcommerceCustomerDetailsBilling::class, 'index'])->name('pages-customer-details-billing');
  Route::get('details/notifications', [EcommerceCustomerDetailsNotifications::class, 'index'])->name('pages-customer-details-notifications');
});




Route::group(['prefix' => 'invoice'], function () {
  Route::get('list', [PaymentController::class, 'index'])->name('pages-invoice-list');
  Route::get('details/{payment}', [PaymentController::class, 'show'])->name('pages-invoice-details');
  Route::get('print/{payment}', [PaymentController::class, 'printInvoice'])->name('pages-invoice-print');
  Route::get('add', [PaymentController::class, 'create'])->name('pages-invoice-add');
  Route::post('add', [PaymentController::class, 'store'])->name('invoice.add');
});


Route::group(['prefix' => 'settings'], function() {
  Route::get('', [EcommerceSettingsDetails::class, 'index'])->name('pages-settings');
  Route::get('details', [EcommerceSettingsDetails::class, 'index'])->name('pages-settings-details');
  Route::get('carousel', [EcommerceSettingsDetails::class, 'showCarouselSettings'])->name('pages-settings-carousel');
  Route::get('payments', [EcommerceSettingsPayments::class, 'index'])->name('pages-settings-payments');
  Route::get('checkout', [EcommerceSettingsCheckout::class, 'index'])->name('pages-settings-checkout');
  Route::get('shipping', [EcommerceSettingsShipping::class, 'index'])->name('pages-settings-shipping');
  Route::get('locations', [EcommerceSettingsLocations::class, 'index'])->name('pages-settings-locations');
  Route::get('notifications', [EcommerceSettingsNotifications::class, 'index'])->name('pages-settings-notifications');
  Route::post('save/store-details', [EcommerceSettingsDetails::class, 'saveStoreDetails'])->name('pages-settings-save-store-details');
});
