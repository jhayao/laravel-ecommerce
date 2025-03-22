<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Http\Requests\StoreOrderItemRequest;
use App\Http\Requests\UpdateOrderItemRequest;
use Illuminate\Http\JsonResponse;

class OrderItemController extends Controller
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
  public function store(StoreOrderItemRequest $request)
  {
    //
  }

  /**
   * Display the specified resource.
   */
  public function show(OrderItem $orderItem)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(OrderItem $orderItem)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(UpdateOrderItemRequest $request, OrderItem $orderItem)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(OrderItem $orderItem)
  {
    //
  }

  public function getOrderDetails(string $order_number)
  {
    $order = Order::where('order_number', $order_number)->first();
    $order_items = OrderItem::where('order_id', $order->id)->get();
    return response()->json(['data' => $order_items]);
  }
}
