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
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'email_verified_at' => now(),
            'role' => User::ROLE_CLIENT,
            'password' => static::$password ??= Hash::make('password'),
            'avatar' => null,
            'auth_provider' => null,
            'provider_id' => null,
            'profile_completed_at' => now(),
            'business_requested_at' => null,
            'business_approved_at' => null,
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => User::ROLE_ADMIN,
        ]);
    }

    public function business(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => User::ROLE_BUSINESS,
            'business_requested_at' => now(),
            'business_approved_at' => now(),
        ]);
    }

    public function client(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => User::ROLE_CLIENT,
        ]);
    }
}
