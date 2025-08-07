<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Device>
 */
class DeviceFactory extends Factory {
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array {

    $hasSrjcTag = fake()->numberBetween(1, 10) <= 9; // 90% chance
    $hasSerial = fake()->numberBetween(1, 10) <= 3;   // 30% chance

    if (!$hasSrjcTag && !$hasSerial) {
      $hasSerial = TRUE;
    }

    return [
      'srjc_tag' => $hasSrjcTag ? fake()->unique()->randomNumber(5) : NULL,
      'serial_number' => $hasSerial ? fake()->unique()->bothify('#??#??#') : NULL,
      'model_number' => fake()->randomElement(
        [
          'Dell Latitude 9450',
          'Dell Latitude 7350',
          'Dell Latitude 7455',
          'Dell Latitude 7450',
          'Dell Latitude 7650',
          'Dell Latitude 5550',
          'Dell Latitude 5455',
          null,
          null,
          null,
          null,
          null,
        ]
      )
    ];
  }
}
