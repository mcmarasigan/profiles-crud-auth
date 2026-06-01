<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validates the registration form input on the server side.
 * Works alongside client-side HTML5/JS validation for full coverage.
 */
class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'min:2', 'max:100'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed',
                // Must contain uppercase, lowercase, digit, and special char
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'      => 'Please enter your full name.',
            'name.min'           => 'Name must be at least 2 characters.',
            'email.required'     => 'Please enter a valid email address.',
            'email.email'        => 'The email address format is invalid.',
            'email.unique'       => 'This email is already registered. Please log in.',
            'password.required'  => 'Please create a password.',
            'password.min'       => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
            'password.regex'     => 'Password must contain uppercase, lowercase, number, and special character.',
        ];
    }
}
