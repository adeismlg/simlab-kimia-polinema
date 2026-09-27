<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'tipe' => 'internal',
            'instansi' => 'Politeknik Negeri Malang',
            'no_hp' => '08' . fake()->numerify('##########'),
            'nim_nip' => fake()->numerify('##########'),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => ['email_verified_at' => null]);
    }

    public function eksternal(string $instansi = null): static
    {
        return $this->state(fn (array $attributes) => [
            'tipe' => 'eksternal',
            'instansi' => $instansi ?? fake()->company(),
            'nim_nip' => null,
        ]);
    }

    public function mahasiswa(): static
    {
        return $this->state(fn (array $attributes) => [
            'tipe' => 'internal',
            'instansi' => 'D4 Teknik Kimia, Polinema',
        ]);
    }
}
