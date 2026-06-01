<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Validates user profile create/update form input.
 * Used in ProfileController::store() and ProfileController::update().
 */
class ProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Get the user being updated (null when creating)
        $userId = $this->route('user')?->id;

        $rules = [
            'name'    => ['required', 'string', 'min:2', 'max:100'],
            'email'   => ['required', 'email', 'max:255',
                Rule::unique('users', 'email')->ignore($userId)->whereNull('deleted_at'),
            ],
            'bio'     => ['nullable', 'string', 'max:500'],
            'phone'   => ['nullable', 'string', 'max:20', 'regex:/^[\d\s\+\-\(\)]+$/'],
            'address' => ['nullable', 'string', 'max:255'],
            'avatar'  => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
        ];

        // Only validate role if the current logged-in user is an admin
        // Regular users don't submit the role field when editing their profile.
        if (auth()->user()?->isAdmin()) {
            $rules['role'] = ['required', Rule::in(['admin', 'user'])];
        }

        // Password is optional on update, required on create
        if ($this->isMethod('POST') && !$userId) {
            $rules['password'] = ['required', 'string', 'min:8', 'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/'];
        } else {
            $rules['password'] = ['nullable', 'string', 'min:8', 'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required'      => 'Full name is required.',
            'name.min'           => 'Name must be at least 2 characters.',
            'email.required'     => 'Email address is required.',
            'email.email'        => 'Please enter a valid email address.',
            'email.unique'       => 'This email is already in use by another account.',
            'role.required'      => 'Please select a role.',
            'role.in'            => 'Role must be either admin or user.',
            'phone.regex'        => 'Phone number may only contain digits, spaces, and + - ( ) characters.',
            'avatar.image'       => 'Avatar must be an image file.',
            'avatar.mimes'       => 'Accepted image formats: jpeg, png, jpg, gif, webp.',
            'avatar.max'         => 'Avatar file size may not exceed 2MB.',
            'password.min'       => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
            'password.regex'     => 'Password must contain uppercase, lowercase, number, and special character.',
        ];
    }
}
