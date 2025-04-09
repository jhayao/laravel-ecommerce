<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class EcommerceOrderDetails extends Controller
{
  public function index(string $order_number)
  {
    $order = Order::where('order_number', $order_number)->first();
    return view('content.pages.app-ecommerce-order-details', compact('order'));
  }

  public function getOrderDetails(string $order_number)
  {
    $order = Order::where('order_number', $order_number)->first();
    return response()->json(['data' => [0 => $order]]);
  }
}
