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
      'Library' => ['tailwind_class' => 'bg-green-600', 'weight' => 100],
      'Library - Lost' => ['tailwind_class' => 'bg-slate-500', 'weight' => 700],
      'Imaging - Doyle' => ['tailwind_class' => 'bg-yellow-600', 'weight' => 200],
      'Imaging - Mahoney' => ['tailwind_class' => 'bg-yellow-600', 'weight' => 300],
      'Surplus' => ['tailwind_class' => 'bg-gray-600', 'weight' => 600],
      'Repair' => ['tailwind_class' => 'bg-blue-600', 'weight' => 400],
      'Hold' => ['tailwind_class' => 'bg-stone-500', 'weight' => 500],
    ];

    foreach ($statuses as $name => $attributes) {
      Status::firstOrCreate(
        ['status_name' => $name],
        $attributes
      );
    }
  }

}
