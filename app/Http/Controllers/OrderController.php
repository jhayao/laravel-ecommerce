<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
  public function store(StoreOrderRequest $request): JsonResponse
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
        'invoice_id' => uniqid('INV-'),
        'method' => $request->paymentMethod,
      ]);

      $order = Order::create([
        'customer_id' => $customer->id,
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

      //add shippment details
      $order->shipment()->create([
        'status' => 'pending',
        'tracking_number' => uniqid('TRK-'),
      ]);


      $order->items()->createMany($cart->products->map(function ($product) {
        return [
          'product_id' => $product->id,
          'quantity' => $product->pivot->quantity,
          'price' => $product->price,
        ];
      }));

      $cart->products()->detach();

      DB::commit();
      return response()->json(['message' => 'Order created successfully', 'data' => $order], 201);
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

  public function getOrderList()
  {
    $orders = Order::all();
    return response()->json(['data' => $orders]);
  }

  public function updateOrderStatus(Request $request, Order $order): JsonResponse
  {
    $request->validate([
      'orderStatus' => 'required|string|in:pending,processing,completed,declined',
      'shipmentStatus' => 'required|string|in:pending,shipped,delivered,fail',
    ]);

    $order->status = $request->orderStatus;
    $latestShipment = $order->shipment()->latest()->first();

    $order->shipment()->updateOrCreate(
      [
        'order_id' => $order->id, // Ensure the update is for the same order
        'status' => $request->shipmentStatus, // Look for an existing status for this order
      ],
      [
        'tracking_number' => $latestShipment ? $latestShipment->tracking_number : uniqid('TRK-'),
        'shipped_at' => $request->shipmentStatus === 'shipped' ? now() : ($latestShipment ? $latestShipment->shipped_at : null),
        'delivered_at' => $request->shipmentStatus === 'delivered' ? now() : null,
        'updated_at' => now(), // Update the timestamp
      ]
    );


    if ($request->shipmentStatus === 'delivered') {
      $order->payment()->update([
        'status' => 'success',
      ]);
    }

    $order->save();
    return response()->json(
      [
        'success' => true,
        'message' => 'Order status updated successfully', 'data' => $order
      ]
    );
  }
}
