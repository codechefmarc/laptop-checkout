<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder {
  /**
   * Run the database seeds.
   */
  public function run(): void {
    $statuses = ['Library', 'Imaging', 'Surplus', 'Repair'];

    foreach ($statuses as $status) {
      Status::firstOrCreate(['status_name' => $status]);
    }
  }
}
