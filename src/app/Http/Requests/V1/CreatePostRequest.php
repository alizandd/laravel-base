<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class CreatePostRequest extends FormRequest
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
            'content' => 'required|string',
            'media.*' => 'nullable|file|mimes:jpg,jpeg,png,mp4|max:20480', // 20MB max file size
        ];
    }

    public function messages()
    {
        return [
            'content.required' => 'The content is required.',
            'media.*.mimes' => __('mimes',['attribute'=>'مدیا','value'=>'pg, jpeg, png, and mp4']),
            'media.*.max' => __('max_digits',['attribute'=>'مدیا','value'=>'20MB']),
        ];
    }
}
