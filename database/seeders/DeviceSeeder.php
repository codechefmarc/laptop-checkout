<?php

namespace Database\Seeders;

use App\Models\Device;
use Illuminate\Database\Seeder;

/**
 * Creates dummy devices in the database.
 */
class DeviceSeeder extends Seeder {

  /**
   * Run the database seeds.
   */
  public function run(): void {
    Device::factory(100)->create();
  }

}
