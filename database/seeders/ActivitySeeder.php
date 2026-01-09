<?php

namespace Database\Seeders;

use App\Models\Activity;
use Illuminate\Database\Seeder;

/**
 * Creates dummy activity data.
 */
class ActivitySeeder extends Seeder {

  /**
   * Run the database seeds.
   */
  public function run(): void {
    Activity::factory(200)->create();
  }

}
