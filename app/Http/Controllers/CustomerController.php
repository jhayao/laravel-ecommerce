<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class CustomerController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index(): View
  {
    return view('content.pages.app-ecommerce-customer-all');
  }

  public function customerList(): JsonResponse {
      $customers = Customer::with('orders')->get();
    return response()->json(["data" => $customers], 200);
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
  public function store(StoreCustomerRequest $request)
  {
    //
  }

  /**
   * Display the specified resource.
   */
  public function show(Customer $customer): View
  {
    return view('content.pages.app-ecommerce-customer-details-overview',compact('customer'));
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Customer $customer)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(UpdateCustomerRequest $request)
  {
    $request->validated();
    $customer = auth()->user();
    if ($request->hasFile('profile_picture')) {
      $file = $request->file('profile_picture');
      $filename = time() . '.' . $file->getClientOriginalExtension();
      $file->move(public_path('images'), $filename);
      $customer->profile_picture = 'images/'. $filename;
      $customer->save();
    }
    $customer->update($request->except(['profile_picture']));
    return response()->json($customer, 200);
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Customer $customer)
  {
    //
  }

  public function customerOrders(Customer $customer): JsonResponse
  {
      $customerOrders = $customer->orders()->get();
      return response()->json(["data" => $customerOrders], 200);
  }
}
