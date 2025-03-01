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
    $customer->address()->create($request->all());
    return response()->json($customer->address, 201);
  }
}
