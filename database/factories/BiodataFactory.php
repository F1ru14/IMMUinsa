<?php

namespace Database\Factories;

use App\Enums\Komisariat;
use App\Models\Biodata;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Biodata>
 */
class BiodataFactory extends Factory
{
    protected $model = Biodata::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'full_name' => fake()->name(),
            'phone_number' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'date_of_birth' => fake()->date('Y-m-d', '-18 years'),
            'gender' => fake()->randomElement(['male', 'female']),
            'avatar' => null,
            'institution' => Komisariat::Avenzoar->value,
            'expertise' => fake()->randomElement(['Kaderisasi', 'Public Speaking', 'Keorganisasian']),
        ];
    }
}
