<?php

namespace App\Api\V1\Requests\Customer;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CustomerStoreRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'min:2', 'max:255'],
            'last_name' => ['nullable', 'min:2', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
        ];
    }
}
