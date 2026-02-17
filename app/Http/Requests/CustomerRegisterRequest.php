<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRegisterRequest extends FormRequest
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
            'first_name'    => 'required|string|max:255',
            'last_name'     => 'required|string|max:255',
            'email'         => 'required|string|email|max:255|unique:customers,email',
            'password'      => 'required|string|min:8|confirmed',
            'phone'         => 'nullable|string|max:20',
            'address'       => 'nullable|string|max:500',
            'customer_type' => 'nullable|in:retail,wholesale,regular'
        ];
    }

    /**
     * Get custom error messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'first_name.required'   => 'First name is required',
            'last_name.required'    => 'Last name is required',
            'email.required'        => 'Email address is required',
            'email.email'           => 'Please provide a valid email address',
            'email.unique'          => 'This email is already registered',
            'password.required'     => 'Password is required',
            'password.min'          => 'Password must be at least 8 characters',
            'password.confirmed'    => 'Password confirmation does not match',
            'customer_type.in'      => 'Invalid customer type selected'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'first_name'    => 'first name',
            'last_name'     => 'last name',
            'customer_type' => 'customer type'
        ];
    }
}
