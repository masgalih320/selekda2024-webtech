<?php

namespace App\Http\Requests\Auth;

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
            'name' => 'required|string',
            'username' => 'required|string|unique:users',
            'phone' => 'required|numeric',
            'email' => 'required|email|unique:users',
            'date_of_birth' => 'required|date',
            'profile_picture' => 'mimes:png,jpg,jpeg|mimetypes:image/png,image/jpg,image/jpeg|max:10000',
            'password' => 'required|string|min:5|confirmed',
            'password_confirmation' => 'required|string',
        ];
    }
}
