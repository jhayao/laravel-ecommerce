<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;

class Dashboard extends Controller
{
  public function index()
  {
    return view('content.pages.dashboard');
  }
}
