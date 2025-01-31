<?php

namespace App\Api\V1\Requests\Customer;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomerUpdateRequest extends CustomerStoreRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        $rules = parent::rules();
        $rules['email'] = ['required', 'email', 'max:255', Rule::unique('users')->ignore($this->route('customer'))];

        return $rules;
    }
}
