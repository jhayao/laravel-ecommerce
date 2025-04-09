<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use App\Models\ShopSetting;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EcommerceSettingsDetails extends Controller
{
  public function index()
  {
    $shop_settings = ShopSetting::where('name', 'shop_settings')
      ->where('is_active', 1)
      ->where('type', 'store_details')
      ->first();
    return view('content.pages.app-ecommerce-settings-details', [
      'shop_settings' => $shop_settings ? json_decode($shop_settings->value, true) : null,
    ]);
  }

  public function showCarouselSettings(): View
  {
    return view('content.pages.app-ecommerce-settings-carousel');
  }
  public function saveStoreDetails(Request $request): \Illuminate\Http\RedirectResponse
  {
    $request->validate([
      'store_name' => 'required|string|max:255',
      'email' => 'required|email|max:255',
      'phone' => 'required|string|max:20',
      'business_name' => 'required|string|max:255',
      'building' => 'string|max:255',
      'street' => 'required|string|max:100',
      'city' => 'required|string|max:100',
      'province' => 'required|string|max:100',
      'zip' => 'required|string|max:20',
    ]);

    $shop_settings = $request->only([
      'store_name',
      'email',
      'phone',
      'business_name',
      'building',
      'street',
      'city',
      'province',
      'zip',
    ]);

    ShopSetting::updateOrCreate(
      [
        'name' => 'shop_settings',
        'type' => 'store_details'
      ],
      [
        'value' => json_encode($shop_settings)
      ]
    );

    // Save the store details to the database or perform any other necessary actions

    return redirect()->back()->with('success', 'Store details saved successfully.');
  }

  public function saveCarouselImages(Request $request): \Illuminate\Http\RedirectResponse
  {
    $request->validate([
      'carousel_images' => 'required|array',
      'carousel_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $carousel_images = [];
    foreach ($request->file('carousel_images') as $image) {
      $path = $image->store('carousel_images', 'public');
      $carousel_images[] = $path;
    }

    ShopSetting::updateOrCreate(
      [
        'name' => 'shop_settings',
        'type' => 'carousel_images'
      ],
      [
        'value' => json_encode($carousel_images)
      ]
    );

    return redirect()->back()->with('success', 'Carousel images saved successfully.');
  }


}
