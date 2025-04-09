<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerAddressStoreRequest;
use Illuminate\Http\JsonResponse;

class CustomerAddressController extends Controller
{
  public function storeAddress(CustomerAddressStoreRequest $request): JsonResponse
  {
    $request->validated();
    $customer = auth()->user();
    $customer->address()->updateOrCreate(
      ['customer_id' => $customer->id], // Condition to check existing record
      $request->all() // Data to update or insert
    );
    return response()->json(['success' => true, 'message' => 'Address saved successfully']);
  }
}
