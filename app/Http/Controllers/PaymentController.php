<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use App\Models\ShopSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class PaymentController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index(): View
  {
    return view('content.pages.app-invoice-list');
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
  public function store(StorePaymentRequest $request)
  {
    //
  }

  /**
   * Display the specified resource.
   */
  public function show(Payment $payment): View
  {
    $address = str_replace('<br>', ', ', $payment->order->customer->address()->first()->full_address);
    $order_items = $payment->order->items;
    $shop_setting = ShopSetting::where('type', 'store_details')->first()->value;
    $storeDetails = json_decode($shop_setting, true);
    $admin_phone = $storeDetails['phone'];
    $store_name = $storeDetails['store_name'];
    $admin_address = $storeDetails['building'] . ', ' . $storeDetails['street'] . ', ' . $storeDetails['city'] . ', ' . $storeDetails['province'];
    return view('content.pages.app-invoice-preview', [
      'payment' => $payment,
      'delivered_at' => $payment->order->shipment()->where('status', 'delivered')->first()->delivered_at,
      'address' => $address,
      'order_items' => $order_items,
      'admin_address' => $admin_address,
      'admin_phone' => $admin_phone,
      'store_name' => $store_name,
    ]);
  }

  public function printInvoice(Payment $payment): View
  {
    $address = str_replace('<br>', ', ', $payment->order->customer->address()->first()->full_address);
    $order_items = $payment->order->items;
    $pageConfigs = ['myLayout' => 'blank'];
    return view('content.pages.app-invoice-print', [
      'payment' => $payment,
      'delivered_at' => $payment->order->shipment()->where('status', 'delivered')->first()->delivered_at,
      'address' => $address,
      'order_items' => $order_items,
      'pageConfigs' => $pageConfigs,
    ]);
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Payment $payment)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(UpdatePaymentRequest $request, Payment $payment)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Payment $payment)
  {
    //
  }

  public function getPaymentList(): JsonResponse
  {
    $payments = Payment::with('order')->get();
    return response()->json([
      'data' => $payments,
    ]);
  }
}
