<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = $this->faker->randomElement(User::$statuses);

        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'status' => $status,
            'type' => User::CUSTOMER,
            'email' => $this->faker->unique()->safeEmail(),
            'password' => $status === User::ACTIVE ? Hash::make('password') : null,
            'token' => $status === User::PENDING ? Str::random(100) : null,
            'email_verified_at' => $status === User::ACTIVE ? $this->faker->dateTimeBetween('-2 years', '-1 day') : null,
            'token_expired_at' => $status === User::PENDING ? $this->faker->dateTimeBetween('+1 week', '+1 month') : null,
        ];
    }
}
