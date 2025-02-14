<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EcommerceCustomerAll extends Controller
{
  public function index()
  {
    return view('content.pages.app-ecommerce-customer-all');
  }
}
