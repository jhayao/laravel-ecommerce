<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Http\Requests\StoreCartRequest;
use App\Http\Requests\UpdateCartRequest;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;

class CartController extends Controller
{

  public function addToCart(StoreCartRequest $request): JsonResponse
  {
    $request->validated();
    $customer = Customer::find($request->customer_id);
    $cart = $customer->cart;
    $cart->products()->attach($request->product_id, ['quantity' => $request->quantity]);
    return response()->json($cart, 201);
  }

  public function getCartList(int $customer_id): JsonResponse
  {
    $customer = Customer::find($customer_id);
    $cart = $customer->cart;
    if(!$cart) {
      return response()->json([], 200);
    }
    return response()->json($cart->products, 200);
  }
}
