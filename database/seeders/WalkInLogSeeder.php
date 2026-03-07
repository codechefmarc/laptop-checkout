<?php

namespace Database\Seeders;

use App\Models\WalkInLog;
use Illuminate\Database\Seeder;

/**
 * Creates real support categories in the database.
 */
class WalkInLogSeeder extends Seeder {

  /**
   * Run the database seeds.
   */
  public function run(): void {
    WalkInLog::factory(200)->create();
  }

}
