<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

/**
 * Seeds the database with roles.
 */
class RoleSeeder extends Seeder {

  /**
   * Seeds the database with default roles.
   */
  public function run() {
    Role::create([
      'name' => 'admin',
      'display_name' => 'Administrator',
      'description' => 'Full access to all features including user management',
    ]);

    Role::create([
      'name' => 'data_entry',
      'display_name' => 'Data Entry',
      'description' => 'Can create and edit data but cannot manage users',
    ]);

    Role::create([
      'name' => 'read_only',
      'display_name' => 'Read Only',
      'description' => 'Can only view data, no editing permissions',
    ]);
  }

}
