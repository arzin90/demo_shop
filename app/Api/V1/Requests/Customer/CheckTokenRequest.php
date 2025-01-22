<?php

namespace App\Api\V1\Requests\Customer;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\Rule;

class CheckTokenRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        RateLimiter::hit(request()->ip(), 60 * config('custom.decay_minutes'));

        return [
            'token' => ['required', Rule::exists('users')]
        ];
    }
}
