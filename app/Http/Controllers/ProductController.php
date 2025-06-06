<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Image;
use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\ProductImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    DB::beginTransaction();

    $product = Product::create([
      'name' => $request->input('productTitle'),
      'price' => $request->input('productPrice'),
      'description' => $request->input('description'),
      'sku' => $request->input('productSku'),
      'barcode' => $request->input('productBarcode'),
      'discounted_price' => $request->input('productDiscountedPrice'),
      'status' => $request->input('productStatus'),
      'category_id' => $request->input('productCategory'),
      'stock' => $request->input('productStocks')
    ]);

    if ($request->has('productImage')) {
      $product->images()->attach($request->input('productImage'));
    }

    DB::commit();
    return response()->json(['success' => (bool)$product, 'message' => 'Product created successfully']);
  }

  /**
   * Display the specified resource.
   */
  public function show(Product $product): JsonResponse
  {
    $product_data = Product::withTrashed()->join('categories', 'products.category_id', '=', 'categories.id')
      ->select('products.*',
        'categories.title as category_title',
        DB::raw('\'sale\' as label'),
        DB::raw('MIN(images.image) AS image'),
        DB::raw('CASE WHEN products.status = \'publish\' THEN 2 WHEN products.status = \'deleted\' THEN 1 ELSE 3 END AS status_id'))
      ->leftJoin('product_images', 'products.id', '=', 'product_images.product_id')
      ->leftJoin('images', 'product_images.image_id', '=', 'images.id')
      ->groupBy('products.id','categories.title')
      ->where('products.id', $product->id)
      ->get();


    $product_data = $product_data->map(function ($product) {
      $product->image = $product->image ? Storage::url($product->image) : null;
      return $product;
    });
    return response()->json($product_data->load('images')->first());
  }

  public function restore(int $product_id): JsonResponse
  {
    $product = Product::withTrashed()->findOrFail($product_id);
    $product->restore();
    $product->status = 'publish';
    $product->save();
    return response()->json(['success' => true, 'message' => 'Product restored successfully']);
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Product $product)
  {
    $categories = Category::all();
//    $product = Product::withTrashed()->findOrFail($product_id);
    return view('content.pages.app-ecommerce-product-add', ['categories' => $categories, 'product' => $product, 'edit' => true]);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(UpdateProductRequest $request, Product $product)
  {
    DB::beginTransaction();
    if ($request->has('productImage')) {
      $product->images()->sync($request->input('productImage'));
    }

    $product->update([
      'name' => $request->input('productTitle'),
      'price' => $request->input('productPrice'),
      'description' => $request->input('description'),
      'sku' => $request->input('productSku'),
      'barcode' => $request->input('productBarcode'),
      'discounted_price' => $request->input('productDiscountedPrice'),
      'status' => $request->input('productStatus'),
      'category_id' => $request->input('productCategory'),
      'stock' => $request->input('productStocks')
    ]);
    DB::commit();
    return response()->json(['success' => true, 'message' => 'Product updated successfully']);
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Product $product): JsonResponse
  {
    $product->status = 'deleted';
    $product->save();
    $product->delete();

    return response()->json(['success' => true, 'message' => 'Product deleted successfully']);
  }

  public function productImageUpload(Request $request): JsonResponse
  {
    $message = [];
    try {
      $file = $request->file('file');
      $image_path = $file->store('public/images/products');
      $image_path = str_replace('public/', '', $image_path);
      $image = new Image();
      $image->image = $image_path;
      $image->save();
      $message = ['success' => true, 'message' => 'Image uploaded successfully', 'original_name' => $file->getClientOriginalName(), 'image' => $image->raw_image, 'id' => $image->id];
    } catch (\Exception $e) {
      $message = ['success' => false, 'message' => $e->getMessage()];
    }

    return response()->json($message, 200);
  }

  public function productImageDelete(Request $request): JsonResponse
  {
    $request->validate([
      'image' => 'required|string',
      'image_id' => 'required|integer|exists:images,id'
    ]);
    $status = Storage::exists('public/' . $request->image) && Storage::delete('public/' . $request->image);
    if (!$status) {
      return response()->json(['message' => 'Image not found'], 404);
    }
    Image::where('id', $request->id)->delete();
    return response()->json(['message' => 'Image deleted successfully'], 200);
  }

  public function getProductList(?string $admin = null, ?int $product_id = null): JsonResponse
  {
    $product_data = Product::withTrashed()->join('categories', 'products.category_id', '=', 'categories.id')
      ->select('products.*',
        'categories.title as category_title',
        DB::raw('\'sale\' as label'),
        DB::raw('MIN(images.image) AS image'),
        DB::raw('CASE WHEN products.status = \'publish\' THEN 2 WHEN products.status = \'deleted\' THEN 1 ELSE 3 END AS status_id'))
      ->leftJoin('product_images', 'products.id', '=', 'product_images.product_id')
      ->leftJoin('images', 'product_images.image_id', '=', 'images.id')
      ->groupBy('products.id','categories.title')
      ->get();

    if ($admin) {
      $product_data = $product_data->map(function ($product) {
        $product->image = $product->image ? Storage::url($product->image) : null;
        $product->description = strip_tags($product->description);
        $product->description = strlen($product->description) > 50 ? substr($product->description, 0, 50) . '...' : $product->description;
        return $product;
      });
      $data = ['data' => $product_data->load('images')];
      return response()->json($data);
    }
    $product_data = $product_data->map(function ($product) {
      $product->image = $product->image ? Storage::url($product->image) : null;
      return $product;
    });
    return response()->json($product_data->load('images'));
  }

  public function getProductListShop(): JsonResponse
  {
    $products = Product::join('categories', 'products.category_id', '=', 'categories.id')
      ->select('products.*', 'categories.title as category', DB::raw('"sale" as label'), DB::raw('MIN(images.image) AS image'))
      ->leftJoin('product_images', 'products.id', '=', 'product_images.product_id')
      ->leftJoin('images', 'product_images.image_id', '=', 'images.id')
      ->groupBy('products.id')
      ->get();

    $product_data = ['products' => $products];

    return response()->json($product_data);
  }
}
