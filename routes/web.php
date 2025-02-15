<?php

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
  Route::get('list', [EcommerceProductList::class, 'index'])->name('pages-product-list');
  Route::get('add', [EcommerceProductAdd::class, 'index'])->name('pages-product-add');
  Route::get('category', [CategoryController::class, 'index'])->name('pages-product-category');
  Route::post('category', [CategoryController::class, 'create'])->name('categories.create');

});
//Orders
Route::group(['prefix' => 'orders'], function () {
  Route::get('list', [EcommerceOrderList::class, 'index'])->name('pages-order-list');
  Route::get('details', [EcommerceOrderDetails::class, 'index'])->name('pages-order-details');
});
//Customers
Route::group(['prefix' => 'customers'], function () {
  Route::get('all', [EcommerceCustomerAll::class, 'index'])->name('pages-customer-all');
  Route::get('details/overview', [EcommerceCustomerDetailsOverview::class, 'index'])->name('pages-customer-details-overview');
  Route::get('details/security', [EcommerceCustomerDetailsSecurity::class, 'index'])->name('pages-customer-details-security');
  Route::get('details/billing', [EcommerceCustomerDetailsBilling::class, 'index'])->name('pages-customer-details-billing');
  Route::get('details/notifications', [EcommerceCustomerDetailsNotifications::class, 'index'])->name('pages-customer-details-notifications');
});



Route::get('/app/ecommerce/manage/reviews', [EcommerceManageReviews::class, 'index'])->name('pages-manage-reviews');
Route::get('/app/ecommerce/referrals', [EcommerceReferrals::class, 'index'])->name('pages-referrals');
Route::get('/app/ecommerce/settings/details', [EcommerceSettingsDetails::class, 'index'])->name('pages-settings-details');
Route::get('/app/ecommerce/settings/payments', [EcommerceSettingsPayments::class, 'index'])->name('pages-settings-payments');
Route::get('/app/ecommerce/settings/checkout', [EcommerceSettingsCheckout::class, 'index'])->name('pages-settings-checkout');
Route::get('/app/ecommerce/settings/shipping', [EcommerceSettingsShipping::class, 'index'])->name('pages-settings-shipping');
Route::get('/app/ecommerce/settings/locations', [EcommerceSettingsLocations::class, 'index'])->name('pages-settings-locations');
Route::get('/app/ecommerce/settings/notifications', [EcommerceSettingsNotifications::class, 'index'])->name('pages-settings-notifications');
