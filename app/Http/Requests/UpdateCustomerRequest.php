<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
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
      'first_name' => ['required', 'string', 'max:255'],
      'middle_name' => ['sometimes', 'string', 'max:255', 'nullable'],
      'last_name' => ['required', 'string', 'max:255'],
      'email' => ['required', 'email', 'max:255'],
      'phone_number' => ['required', 'string', 'max:255'],
      'profile_picture' => ['file', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048', 'nullable'],
    ];
  }
}
