<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use App\GraphQL\Enums\Status;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
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
            'country_code' => $countryCode = fake()->numerify('#####'),  // Generate the country code
            'mobile' => $countryCode . fake()->numerify('##########'),   // Concatenate the country code with the mobile number

            //'email' => fake()->unique()->safeEmail(),
            //'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            //'remember_token' => Str::random(10),
            "status" => Status::Active,
            'mobile_is_verified' => false,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
