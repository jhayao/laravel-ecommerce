<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function generalSettingView(): View
    {
        return view('content.pages.app-ecommerce-settings-details');
    }
}
