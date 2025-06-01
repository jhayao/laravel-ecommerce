<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveFcmTokenRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'token' => 'required|string|max:255',
            'userId' => 'nullable|string|max:255',
            'platform' => 'nullable|string|in:android,ios,web',
            'timestamp' => 'nullable|date',
            'appVersion' => 'nullable|string|max:50',
            'packageName' => 'nullable|string|max:255'
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'token.required' => 'FCM token is required.',
            'token.string' => 'FCM token must be a string.',
            'token.max' => 'FCM token cannot exceed 255 characters.',
            'platform.in' => 'Platform must be one of: android, ios, web.',
            'timestamp.date' => 'Timestamp must be a valid date.',
            'appVersion.max' => 'App version cannot exceed 50 characters.',
            'packageName.max' => 'Package name cannot exceed 255 characters.'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'platform' => $this->platform ?? 'android',
            'timestamp' => $this->timestamp ?? now()
        ]);
    }
}
