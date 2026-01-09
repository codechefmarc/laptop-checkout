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
   * @var array|null
   */
  private static $devicePool = NULL;

  /**
   * Current index for cycling through devices.
   *
   * @var int
   */
  private static $currentIndex = 0;

  /**
   * Define the model's default state.
   *
   * @return array
   *   An array of dummy activity data.
   */
  public function definition(): array {

    if (self::$devicePool === NULL) {
      // Get random devices to reuse frequently.
      self::$devicePool = Device::inRandomOrder()->take(100)->pluck('id')->toArray();
      // Shuffle the pool for randomness.
      shuffle(self::$devicePool);
    }

    $poolSize = count(self::$devicePool);

    // First pass: ensure every device gets at least one activity.
    if (self::$currentIndex < $poolSize) {
      $deviceId = self::$devicePool[self::$currentIndex];
    }
    else {
      // After first pass: random distribution for remaining activities.
      $deviceId = fake()->randomElement(self::$devicePool);
    }

    self::$currentIndex++;

    return [
      'device_id' => $deviceId,
      'status_id' => Status::inRandomOrder()->first()->id,
      'username' => fake()->firstName() . ' ' . fake()->lastName(),
      'notes' => fake()->optional(0.3)->sentence(),
      'created_at' => fake()->dateTimeBetween('-1 week', 'now'),
    ];
  }

}
