<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * Seeds the database with roles.
 */
class RoleSeeder extends Seeder {

  /**
   * Run the database seeds.
   */
  public function run() {
    foreach (config('permissions') as $permission) {
      Permission::firstOrCreate(['name' => $permission]);
    }

    $admin = Role::create(['name' => 'admin', 'display_name' => 'Administrator']);
    $admin->givePermissionTo(Permission::all());

    $itStaff = Role::create(['name' => 'it_staff', 'display_name' => 'IT Staff']);
    $itStaff->givePermissionTo(['laptops.edit', 'laptops.reports']);

    $laptopReporting = Role::create(['name' => 'read_only_laptop', 'display_name' => 'Laptop Reporting']);
    $laptopReporting->givePermissionTo(['laptops.reports']);

    $supportReporting = Role::create(['name' => 'read_only_support', 'display_name' => 'Support Reporting']);
    $supportReporting->givePermissionTo(['walkin.reports']);

    $student = Role::create(['name' => 'student', 'display_name' => 'Student']);
    $student->givePermissionTo(['laptops.edit', 'laptops.reports', 'walkin.edit']);
  }

}
