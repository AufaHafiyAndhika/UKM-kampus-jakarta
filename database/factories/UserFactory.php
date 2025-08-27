<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
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
        $year = fake()->numberBetween(2020, 2024);
        $sequence = fake()->unique()->numberBetween(1000, 9999);

        return [
            'nim' => $year . $sequence,
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'phone' => fake()->phoneNumber(),
            'gender' => fake()->randomElement(['male', 'female']),
            'faculty' => fake()->randomElement([
                'Fakultas Teknik Elektro',
                'Fakultas Rekayasa Industri',
                'Fakultas Informatika',
                'Fakultas Ekonomi dan Bisnis',
                'Fakultas Komunikasi dan Bisnis',
                'Fakultas Industri Kreatif',
                'Fakultas Ilmu Terapan'
            ]),
            'major' => fake()->randomElement([
                'Teknik Informatika',
                'Sistem Informasi',
                'Teknik Elektro',
                'Teknik Industri',
                'Manajemen',
                'Akuntansi',
                'Ilmu Komunikasi',
                'Desain Komunikasi Visual'
            ]),
            'batch' => (string) $year,
            'bio' => fake()->optional()->sentence(),
            'status' => 'active',
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
}
