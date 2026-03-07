<?php

namespace Database\Factories;

use App\Models\SupportCategory;
use App\Models\WalkInLog;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Provides dummy walk-in log data for the database.
 *
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WalkInLog>
 */
class WalkInLogFactory extends Factory {

  /**
   * Random support categories.
   *
   * @var array|null
   */
  private static $categories = NULL;

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
   *   An array of dummy data.
   */
  public function definition(): array {

    return [
      'username' => fake()->firstName() . ' ' . fake()->lastName(),
      'description' => fake()->optional(0.3)->sentence(),
      'created_at' => fake()->dateTimeBetween('-1 week', 'now'),
      'escalated' => fake()->boolean(20),
      'duration_minutes' => fake()->optional(0.8)->numberBetween(5, 120),
    ];
  }

  /**
   * Add support categories after creation.
   */
  public function configure(): static {
    return $this->afterCreating(function (WalkInLog $walkIn) {

      // Initialize categories on first run.
      if (self::$categories === NULL) {
          self::$categories = SupportCategory::inRandomOrder()->take(7)->pluck('id')->toArray();
          shuffle(self::$categories);
      }

      self::$currentIndex++;
      $poolSize = count(self::$categories);

      if (self::$currentIndex <= $poolSize) {
          $categoryId = self::$categories[self::$currentIndex - 1];
          $walkIn->supportCategories()->attach($categoryId);
      }
      else {
        $randomCategories = fake()->randomElements(
          self::$categories,
          fake()->numberBetween(1, min(3, count(self::$categories)))
        );
        $walkIn->supportCategories()->attach($randomCategories);
      }
    });
  }

}
