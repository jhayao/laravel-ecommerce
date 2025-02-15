<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
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
      'title' => ['required', 'string', 'max:255'],
      'description' => ['required', 'string', 'max:255'],
      'slug' => ['required', 'string', 'max:255', 'unique:categories'],
      'category_image' => ['required', 'file', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
      'parent_id' => ['nullable', 'integer', 'exists:categories,id'],
      'status' => ['required', 'string', 'in:scheduled,publish,inactive'],
    ];
  }

  /**
   * Get the error messages for the defined validation rules.
   *
   * @return array<string, string>
   */
  public function messages(): array
  {
    return [
      'title.required' => 'Title is required',
      'description.required' => 'Description is required',
      'slug.required' => 'Slug is required',
      'image.required' => 'Image is required',
      'parent_id.required' => 'Parent ID is required',
      'status.required' => 'Status is required',
    ];
  }

  public function failedValidation(Validator $validator)
  {
    return response()->json($validator->errors(), 422);
  }
}
