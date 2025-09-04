<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;

/**
 * Creates real statuses in the database.
 */
class StatusSeeder extends Seeder {

  /**
   * Run the database seeds.
   */
  public function run(): void {
    $statuses = [
      'Library' => 'bg-green-600',
      'Imaging' => 'bg-yellow-600',
      'Surplus' => 'bg-gray-600',
      'Repair' => 'bg-blue-600',
      'Hold' => 'bg-stone-500',
    ];

    foreach ($statuses as $name => $class) {
      Status::firstOrCreate(
        [
          'status_name' => $name,
          'tailwind_class' => $class,
        ]);
    }
  }

}
