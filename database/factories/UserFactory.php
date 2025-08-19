<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Provides dummy user data for the database.
 *
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory {
  /**
   * The current password being used by the factory.
   */
  protected static ?string $password;

  /**
   * Define the model's default state.
   *
   * @return array
   *   An array of dummy user data.
   */
  public function definition(): array {
    return [
      'first_name' => fake()->firstName(),
      'last_name' => fake()->lastName(),
      'email' => fake()->unique()->safeEmail(),
      'email_verified_at' => now(),
      'password' => static::$password ??= Hash::make('password'),
      'remember_token' => Str::random(10),
      'role_id' => fake()->numberBetween(1, 3),
    ];
  }

  /**
   * Indicate that the model's email address should be unverified.
   */
  public function unverified(): static {
    return $this->state(fn (array $attributes) => [
      'email_verified_at' => NULL,
    ]);
  }

}
