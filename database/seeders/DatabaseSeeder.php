<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Creates dummy data in the database.
 */
class DatabaseSeeder extends Seeder {

  /**
   * Seed the application's database.
   */
  public function run(): void {
    $this->call(RoleSeeder::class);
    $this->call(UserSeeder::class);
    $this->call(PoolSeeder::class);
    $this->call(DeviceSeeder::class);
    $this->call(StatusSeeder::class);
    $this->call(ActivitySeeder::class);
    $this->call(SupportCategorySeeder::class);
    $this->call(WalkInLogSeeder::class);
  }

}
