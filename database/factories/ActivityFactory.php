<?php

namespace Database\Factories;

use App\Models\Device;
use App\Models\Status;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Provides dummy activity data for the database.
 *
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Activity>
 */
class ActivityFactory extends Factory {

  /**
   * A randomly selected pool of devices.
   *
   * @var devicePool
   */
  private static $devicePool = NULL;

  /**
   * Define the model's default state.
   *
   * @return array
   *   An array of dummy activity data.
   */
  public function definition(): array {

    if (self::$devicePool === NULL) {
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
