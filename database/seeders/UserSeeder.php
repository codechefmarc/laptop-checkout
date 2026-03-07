<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Seeds the database with users.
 */
class UserSeeder extends Seeder {

  /**
   * Run the database seeds.
   */
  public function run() {
    $admin = User::factory()->create([
      'first_name' => 'Admin',
      'last_name'  => 'User',
      'email'      => 'admin@example.com',
      'password'   => Hash::make('password'),
    ]);
    $admin->assignRole('admin');

    $itStaff = User::factory()->create([
      'first_name' => 'IT',
      'last_name'  => 'Staff',
      'email'      => 'itstaff@example.com',
      'password'   => Hash::make('password'),
    ]);
    $itStaff->assignRole('it_staff');

    $readOnlyLaptop = User::factory()->create([
      'first_name' => 'Laptop',
      'last_name'  => 'Read Only',
      'email'      => 'readlaptop@example.com',
      'password'   => Hash::make('password'),
    ]);
    $readOnlyLaptop->assignRole('read_only_laptop');

    $readOnlySupport = User::factory()->create([
      'first_name' => 'Support',
      'last_name'  => 'Read Only',
      'email'      => 'readsupport@example.com',
      'password'   => Hash::make('password'),
    ]);
    $readOnlySupport->assignRole('read_only_support');

    $student = User::factory()->create([
      'first_name' => 'Student',
      'last_name'  => 'Worker',
      'email'      => 'student@example.com',
      'password'   => Hash::make('password'),
    ]);
    $student->assignRole('student');
  }

}
