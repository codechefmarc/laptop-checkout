<?php

namespace Database\Seeders;

use App\Models\Pool;
use Illuminate\Database\Seeder;

/**
 * Creates real pools in the database.
 */
class PoolSeeder extends Seeder {

  /**
   * Run the database seeds.
   */
  public function run(): void {
    $statuses = [
      'General' => 'General pool of laptops for all students',
      'CalWorks' => 'Laptops for CalWorks students only',
      'IGNITE' => 'Laptops for IGNITE program students only',
    ];

    foreach ($statuses as $name => $desc) {
      Pool::firstOrCreate(
        [
          'name' => $name,
          'description' => $desc,
        ]);
    }
  }

}
