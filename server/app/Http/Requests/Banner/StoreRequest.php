<?php

namespace App\Http\Requests\Banner;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'title' => 'required|string',
            'image' => 'required|mimes:png,jpg,jpeg|mimetypes:image/x-png,image/jpg,image/jpeg|max:10000',
            'description' => 'required|string',
            'status' => 'required|in:active,inactive',
        ];
    }
}
