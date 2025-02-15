<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//Categories
Route::group(['prefix' => 'categories'], function () {
  Route::get('list', [CategoryController::class, 'getCategories'])->name('pages-category-list');
  Route::post('create', [CategoryController::class, 'create'])->name('categories.create');
});

