<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;

class Dashboard extends Controller
{
  public function index()
  {

    $total_customers = Customer::count();
    $total_orders = Order::count();
    $total_sales = Order::sum('total');
    $total_products = Product::count();
    $total_categories = Category::count();
    $sales_per_month = Order::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(total) as total_sales')
      ->groupBy('month')
      ->orderBy('month', 'asc')
      ->get();
    $sales_per_month = $sales_per_month->map(function ($item) {
      return [
        'month' => $item->month,
        'total_sales' => $item->total_sales,
      ];
    });
    $sales_per_month = $sales_per_month->pluck('total_sales', 'month')->toArray();
    $dashboard_data = [
      'total_users' => $total_customers,
      'total_orders' => $total_orders,
      'total_revenue' => $total_sales,
      'total_products' => $total_products,
      'total_categories' => $total_categories,
    ];
    return view('content.pages.dashboard');
  }
}
