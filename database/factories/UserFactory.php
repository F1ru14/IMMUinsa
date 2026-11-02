<?php

namespace Database\Factories;

use App\Enums\Role;
use App\Models\Biodata;
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
            'username' => Str::lower(Str::replace(['.', ' '], '_', fake()->unique()->userName())),
            'email' => fake()->unique()->safeEmail(),
            'role' => Role::Admin,
            'is_active' => true,
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
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

    /**
     * Indicate that the user is an admin.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => Role::Admin,
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the user is a mentor.
     */
    public function mentor(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => Role::Mentor,
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the user is a fasilitator.
     */
    public function fasilitator(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => Role::Fasilitator,
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the user is a kader.
     */
    public function kader(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => Role::Kader,
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the user is pending approval.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Attach a completed biodata to the user.
     */
    public function withBiodata(): static
    {
        return $this->has(Biodata::factory());
    }

    /**
     * Indicate that the model has two-factor authentication configured.
     */
    public function withTwoFactor(): static
    {
        return $this->state(fn (array $attributes) => [
            'two_factor_secret' => encrypt('secret'),
            'two_factor_recovery_codes' => encrypt(json_encode(['recovery-code-1'])),
            'two_factor_confirmed_at' => now(),
        ]);
    }
}
