<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    //
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(StoreOrderRequest $request)
  {
    $request->validated();
    $customer = auth()->user();
    DB::beginTransaction();
    try {

      $cart = $customer->cart;
      $cart_items = $cart->products;
      //create payment
      $payment = Payment::create([
        'amount' => $cart->total,
        'status' => 'pending',
        'method' => $request->paymentMethod,
      ]);

      $order = Order::create([
        'user_id' => $customer->id,
        'order_number' => uniqid('ORD-'),
        'status' => 'pending',
        'total' => $cart->total,
        'payment_method' => $request->paymentMethod,
        'payment_id' => $payment->id,
      ]);

      //create order items
      $order->items()->createMany($cart_items->map(function ($item) {
        return [
          'product_id' => $item->id,
          'quantity' => $item->pivot->quantity,
          'price' => $item->price,
        ];
      }));


      $order->items()->createMany($request->items);

      DB::commit();
    } catch (\Exception $e) {
      DB::rollBack();

      return response()->json(['message' => 'Failed to create order', 'error' => $e->getMessage()], 500);
    }

  }

  /**
   * Display the specified resource.
   */
  public function show(Order $order)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Order $order)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(UpdateOrderRequest $request, Order $order)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Order $order)
  {
    //
  }
}
