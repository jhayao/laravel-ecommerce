<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteProductFromCartRequest;
use App\Http\Requests\StoreCartRequest;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;

class CartController extends Controller
{

  public function addToCart(StoreCartRequest $request): JsonResponse
  {
    $request->validated();

    $customer = auth()->user();
    $cart = $customer->cart;
    if (!$cart) {
      $cart = $customer->cart()->create();
    }

    $product = $cart->products()->where('product_id', $request->product_id)->first();

    if ($product) {
      // Increment the quantity if the product already exists in the cart
      $cart->products()->updateExistingPivot($request->product_id, [
        'quantity' => $product->pivot->quantity + $request->quantity
      ]);
    } else {
      // Attach the product to the cart if it doesn't exist
      $cart->products()->attach($request->product_id, ['quantity' => $request->quantity]);
    }

    return response()->json($cart, 201);
  }

  public function getCartList(): JsonResponse
  {
    $customer = auth()->user();
    $cart = $customer->cart;
    if (!$cart) {
      return response()->json([], 200);
    }
    $products = $cart->products;
    return response()->json($products->load('images'), 200);
  }

  public function getCartDetails(): JsonResponse
  {
    $customer = auth()->user();
    $cart = $customer->cart;
    if (!$cart) {
      return response()->json(['count' => 0], 200);
    }
    return response()->json(['count' => $cart->products->count(), 'total' => $cart->total], 200);
  }

  public function updateCartQuantity(StoreCartRequest $request): JsonResponse
  {
    $request->validated();
    $customer = auth()->user();
    $cart = $customer->cart;
    if (!$cart) {
      return response()->json(['message' => 'Cart not found'], 404);
    }

    $product = $cart->products()->where('product_id', $request->product_id)->first();

    if (!$product) {
      return response()->json(['message' => 'Product not found in cart'], 404);
    }

    $cart->products()->updateExistingPivot($request->product_id, [
      'quantity' => $request->quantity
    ]);

    return response()->json($cart, 200);
  }

  public function removeProductFromCart(DeleteProductFromCartRequest $request): JsonResponse
  {
    $request->validated();
    $customer = auth()->user();
    $cart = $customer->cart;
    if (!$cart) {
      return response()->json(['message' => 'Cart not found'], 404);
    }

    $product = $cart->products()->where('product_id', $request->product_id)->first();

    if (!$product) {
      return response()->json(['message' => 'Product not found in cart'], 404);
    }

    $cart->products()->detach($request->product_id);

    return response()->json($cart, 200);
  }
}
