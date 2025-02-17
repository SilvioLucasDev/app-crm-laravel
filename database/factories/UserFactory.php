<?php

namespace Database\Factories;

use App\Enums\Can;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'              => fake()->name(),
            'email'             => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'validation_code'   => null,
            'password'          => 'password',
            'remember_token'    => Str::random(10),
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

    public function withPermission(Can|string $key): static
    {
        $key = $key instanceof Can ? $key->value : $key;

        return $this->afterCreating(function (User $user) use ($key) {
            $user->givePermissionTo($key);
        });
    }

    public function admin(): static
    {
        return $this->afterCreating(function (User $user) {
            $user->givePermissionTo(Can::BE_AN_ADMIN);
        });
    }

    public function deleted(): static
    {
        return $this->state(fn () => [
            'deleted_at' => now(),
            'deleted_by' => User::factory()->create(),
        ]);
    }

    public function withValidationCode(): static
    {
        return $this->state(fn () => [
            'email_verified_at' => null,
            'validation_code'   => random_int(100000, 999999),
        ]);
    }
}
