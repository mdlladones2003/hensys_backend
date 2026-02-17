<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomerUpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name'    => 'sometimes|required|string|max:255',
            'last_name'     => 'sometimes|required|string|max:255',
            'email'         => [
                'sometimes',
                'required',
                'email',
                'max:255',
                Rule::unique('customers', 'email')->ignore($this->user()->customer_id, 'customer_id'),
            ],
            'phone'         => 'sometimes|nullable|string|max:20',
            'address'       => 'sometimes|nullable|string|max:500',
            'customer_type' => 'sometimes|nullable|in:retail,wholesale,regular'
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required'   => 'First name cannot be empty',
            'last_name.required'    => 'Last name cannot be empty',
            'email.required'        => 'Email address cannot be empty',
            'email.email'           => 'Please provide a valid email address',
            'email.unique'          => 'This email is already in use',
            'customer_type.in'      => 'Invalid customer type selected'
        ];
    }
}
