<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
      return [
        'productTitle' => ['required', 'string', 'max:255'],
        'description' => ['sometimes', 'string'],
        'productPrice' => ['required', 'numeric'],
        'productCategory' => ['required', 'exists:categories,id'],
        'productImage' => ['required', 'array' , 'min:1', 'max:5'],
        'productStatus' => ['required', 'in:scheduled,inactive,publish'],
        'productSku' => ['required', 'string', 'max:255'],
        'productBarcode' => ['required', 'string', 'max:255'],
        'productStocks' => ['required', 'numeric'],
        'productImage.*' => ['required', 'exists:images,id']
      ];
    }

  public function failedValidation(Validator $validator)
  {
    return response()->json($validator->errors(), 422);
  }
}
