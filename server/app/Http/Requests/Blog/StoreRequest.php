<?php

namespace App\Http\Requests\Blog;

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
            'featured_image' => 'mimes:png,jpg,jpeg|mimetypes:image/x-png,image/jpg,image/jpeg|max:10000',
            'title' => 'required|string',
            'description' => 'required|string',
            'tags' => 'required|string',
        ];
    }
}
