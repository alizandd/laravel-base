<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'mobile' => 'required|string|max:11', // Example validation, adjust as needed
            'code' => 'required|numeric',
            'key' => 'required|string|max:32',
            'mac' => 'required|string|max:17', // MAC addresses are 17 characters long
            'device_type' => 'required|string|in:Tizen', // Validate device_type is exactly "Tizen"
        ];
    }
}
