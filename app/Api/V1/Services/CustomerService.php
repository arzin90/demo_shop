<?php

namespace App\Api\V1\Services;

use App\Helpers\TokenHelper;
use App\Models\User;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Support\Carbon;

class CustomerService
{
    /**
     * @var mixed
     */
    private mixed $customer;

    /**
     * @param array $data
     * @return mixed
     */
    public function store(array $data): mixed
    {
        return User::create(array_merge($data, TokenHelper::getToken()));
    }

    /**
     * @param string $token
     * @return array
     */
    public function checkToken(string $token): array
    {
        $this->customer = User::firstWhere('token', $token);

        if (!$this->customer) {
            return [
                'isValid' => false,
                'message' => __('messages.token.invalid'),
            ];
        }

        if (Carbon::now()->isAfter($this->customer->token_expired_at)) {
            $this->customer->update(TokenHelper::getToken());
            $this->customer->notify(new VerifyEmailNotification());

            return [
                'isValid' => false,
                'message' => __('messages.token.expired'),
            ];
        }

        return [
            'isValid' => true,
            'message' => __('messages.token.valid'),
        ];
    }

    /**
     * @param string $token
     * @param string $password
     * @return array
     */
    public function verifyWithPassword(string $token, string $password): array
    {
        $checkToken = $this->checkToken($token);

        if (!$checkToken['isValid']) {
            return $checkToken;
        }

        if ($this->customer->status == User::PENDING) {
            $this->customer->update([
                'status' => User::ACTIVE,
                'password' => $password,
                'email_verified_at' => now(),
                'token' => null,
                'token_expired_at' => null,
            ]);

            return [
                'isValid' => true,
                'message' => __('messages.verified'),
            ];
        }

        $this->customer->update([
            'password' => $password,
            'token' => null,
            'token_expired_at' => null,
        ]);

        return [
            'isValid' => true,
            'message' => __('messages.password.successfullyChanged'),
        ];
    }
}
