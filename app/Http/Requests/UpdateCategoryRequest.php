<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
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
      if ($this->has('parent_id') && $this->parent_id == 0) {
        $this->merge(['parent_id' => null]);
      }

      return [
        'title' => ['required', 'string', 'max:255'],
        'description' => ['required', 'string', 'max:255'],
        'slug' => ['required', 'string', 'max:255', Rule::unique('categories')->ignore($this->route('category'))],
        'category_image' => ['sometimes', 'file', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        'parent_id' => ['nullable', 'integer', 'exists:categories,id'],
        'status' => ['required', 'string', 'in:scheduled,publish,inactive'],
      ];
    }
}
