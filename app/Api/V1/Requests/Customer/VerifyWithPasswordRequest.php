<?php

namespace App\Api\V1\Requests\Customer;

use Illuminate\Contracts\Validation\ValidationRule;

class VerifyWithPasswordRequest extends CheckTokenRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            ...parent::rules(),
            'password' => ['required', 'min:6', 'max:100']
        ];
    }
}
