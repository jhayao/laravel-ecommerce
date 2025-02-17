<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Js;
use Illuminate\View\View;

class ProductController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index(): View
  {
    return view('content.pages.app-ecommerce-product-list');
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create(): View
  {
    $categories = Category::all();
    return view('content.pages.app-ecommerce-product-add', ['categories' => $categories]);
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(StoreProductRequest $request)
  {
    $request->validated();

    $product = Product::create([
      'name' => $request->input('productTitle'),
      'price' => $request->input('productPrice'),
      'description' => $request->input('description'),
      'sku' => $request->input('productSku'),
      'barcode' => $request->input('productBarcode'),
      'image' => $request->input('productImage'),
      'discounted_price' => $request->input('productDiscountedPrice'),
      'status' => $request->input('productStatus'),
      'category_id' => $request->input('productCategory'),
      'stock' => $request->input('productStocks')
    ]);
    return response()->json(['success' => (bool)$product, 'message' => 'Product created successfully']);
  }

  /**
   * Display the specified resource.
   */
  public function show(Product $product)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Product $product)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(UpdateProductRequest $request, Product $product)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Product $product): JsonResponse
  {
    dump($product);
    $product->delete();

    return response()->json(['success' => true, 'message' => 'Product deleted successfully']);
  }

  public function productImageUpload(Request $request): JsonResponse
  {
    $message = [];
    try {
      $image_path = $request->file('file')->store('public/images/products');
      $image_path = str_replace('public/', '', $image_path);
      $message = ['success' => true, 'message' => 'Image uploaded successfully', 'image' => $image_path];
    } catch (\Exception $e) {
      $message = ['success' => false, 'message' => $e->getMessage()];
    }

    return response()->json($message, 200);
  }

  public function productImageDelete(Request $request): JsonResponse
  {
    $request->validate([
      'image' => 'required|string'
    ]);
    $status = Storage::exists('public/' . $request->image) && Storage::delete('public/' . $request->image);
    if (!$status) {
      return response()->json(['message' => 'Image not found'], 404);
    }
    return response()->json(['message' => 'Image deleted successfully'], 200);
  }

  public function getProductList(): JsonResponse
  {
    $products = Product::selectRaw('products.id as id,products.description as description, name as product_name, products.image, sku,stock, price, discounted_price,
     CASE
       WHEN products.status = \'publish\' THEN 1
       WHEN products.status = \'scheduled\' THEN 2
       WHEN products.status = \'inactive\' THEN 3
       ELSE 4
     END as status,
     category_id as category, categories.title as category_title')
      ->join('categories', 'products.category_id', '=', 'categories.id')
      ->where('products.deleted_at', null)
      ->get();
    $product_data = ['data' => $products];

    return response()->json($product_data);
  }
}
