<?php

namespace Database\Factories;

use App\Models\Device;
use App\Models\Status;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Activity>
 */
class ActivityFactory extends Factory {

  private static $devicePool = null;

  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array {

  if (self::$devicePool === null) {
    // Get random devices to reuse frequently.
    self::$devicePool = Device::inRandomOrder()->take(8)->pluck('id')->toArray();
  }

  return [
    'device_id' => fake()->randomElement(self::$devicePool),
    'status_id' => Status::inRandomOrder()->first()->id,
    'username' => fake()->firstName() . ' ' . fake()->lastName(),
    'notes' => fake()->optional(0.3)->sentence(),
    'created_at' => fake()->dateTimeBetween('-1 week', 'now'),
    ];
  }
}
