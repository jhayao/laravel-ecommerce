<?php

namespace App\Http\Controllers\API;

use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class CustomerAuthController extends Controller
{
  public function register(Request $request): JsonResponse
  {
    $request->validate([
      'email' => 'required|email|unique:customers,email',
      'password' => 'required|min:6'
    ]);

    $customer = Customer::create([
      'name' => $request->name,
      'email' => $request->email,
      'password' => $request->password,
    ]);

    return response()->json([
      'message' => 'Customer registered successfully',
    ]);
  }

  public function login(Request $request): JsonResponse
  {
    $request->validate([
      'email' => 'required|email',
      'password' => 'required',
    ]);

    $customer = Customer::where('email', $request->email)->first();

    if (!$customer) {
      return response()->json(['error' => 'Email address not found'], 401);
    }

    if (!Hash::check($request->password, $customer->password)) {
      return response()->json(['error' => 'Incorrect password'], 401);
    }

    $token = $customer->createToken('customer-token')->plainTextToken;

    return response()->json(['customer' => $customer,'token' => $token]);
  }

  public function logout(Request $request): JsonResponse
  {
    $request->user()->tokens()->delete();

    return response()->json(['message' => 'Logged out successfully']);
  }

  public function profile(Request $request): JsonResponse
  {
    $customer = $request->user();
    return response()->json($customer->load(['address']));
  }
}
