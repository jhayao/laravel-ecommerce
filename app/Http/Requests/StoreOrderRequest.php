<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    return auth()->check();
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      'first_name' => 'required|string|max:255',
      'last_name' => 'required|string|max:255',
      'country' => 'required|string|max:255',
      'street_address' => 'required|string|max:255',
      'city' => 'required|string|max:255',
      'province' => 'required|string|max:255',
      'zip_code' => 'required|string|max:20',
      'phone_number' => 'required|string|max:20',
      'paymentMethod' => 'required|string|in:cod,credit_card,paypal',
    ];
  }
}
