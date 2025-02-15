<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CategoryController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index(): View
  {
    $category = Category::all();
    return view('content.pages.app-ecommerce-category-list', ['categories' => $category]);
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create(StoreCategoryRequest $request)
  {
    $request->validated();
    $image_path = $request->file('category_image')->store('public/images');
    $image_path = str_replace('public/', '', $image_path);

    $request->merge(['image' => $image_path]);
    $category = Category::create($request->all());
    //return a success => true
    $message = ['success' => (bool)$category, 'message' => 'Category created successfully'];
    return response()->json($message);
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(StoreCategoryRequest $request)
  {
    //
  }

  /**
   * Display the specified resource.
   */
  public function show(Category $category)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Category $category)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(UpdateCategoryRequest $request, Category $category)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Category $category)
  {
    //
  }

  public function getCategories()
  {
    $data = DB::table('categories')
      ->leftJoin('products', 'categories.id', '=', 'products.category_id')
      ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
      ->leftJoin('orders', 'order_items.order_id', '=', 'orders.id')
      ->where(function ($query) {
        $query->whereIn('orders.status', ['processing', 'shipped', 'delivered'])
          ->orWhereNull('orders.id'); // Ensure categories with no orders are included
      })
      ->select(
        'categories.id as id',
        'categories.image as cat_image',
        'categories.title as categories',
        'categories.description as category_detail',
        DB::raw('COALESCE(SUM(order_items.quantity * order_items.price), 0) as total_earnings'), // Return 0 if no orders
        DB::raw('COUNT(products.id) as total_products')
      )
      ->groupBy('categories.id', 'categories.title')
      ->orderBy('categories.title')
      ->get();
    $data = ['data' => $data];

    return response()->json($data);
  }
}
