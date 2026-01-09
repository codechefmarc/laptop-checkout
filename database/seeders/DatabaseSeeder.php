<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Creates dummy data in the database.
 */
class DatabaseSeeder extends Seeder {

  /**
   * Seed the application's database.
   */
  public function run(): void {
    // User::factory(10)->create();

    $this->call(RoleSeeder::class);

    User::factory()->create([
      'first_name' => 'Test',
      'last_name' => 'User',
      'email' => 'test@example.com',
      'role_id' => 1,
    ]);
    $this->call(DeviceSeeder::class);
    $this->call(StatusSeeder::class);
    $this->call(ActivitySeeder::class);
  }

}
