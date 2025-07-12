<?php

namespace App\Http\Requests\User;

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
            'register_name' => 'required|string|max:255',
            'register_email' => 'required|email|unique:users,email',
            'register_password' => 'required|min:8|confirmed',
        ];
    }
    
    public function messages(): array
    {
        return [
            'register_name.required' => 'Please enter your name.',
            'register_name.string' => 'The name must be a valid string.',
            'register_name.max' => 'The name cannot exceed 255 characters.',
    
            'register_email.required' => 'Please enter your email address.',
            'register_email.email' => 'Please enter a valid email address.',
            'register_email.unique' => 'This email is already registered.',
    
            'register_password.required' => 'Please enter your password.',
            'register_password.min' => 'Password must be at least 8 characters long.',
            'register_password.confirmed' => 'Passwords do not match.',
        ];
}

}
