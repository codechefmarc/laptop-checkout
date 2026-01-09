<?php

/**
 * @file
 * Add Hold status to statuses table.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {

  /**
   * Run the migrations.
   */
  public function up(): void {

    DB::table('statuses')
      ->where('status_name', 'Imaging')
      ->update(['status_name' => 'Imaging - Doyle']);

    DB::table('statuses')
      ->insert([
        'status_name' => 'Library - Lost',
        'tailwind_class' => 'bg-slate-500',
        'weight' => 700,
      ]);

    DB::table('statuses')
      ->insert([
        'status_name' => 'Imaging - Mahoney',
        'tailwind_class' => 'bg-yellow-600',
        'weight' => 300,
      ]);
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void {

    DB::table('statuses')
      ->where('status_name', 'Imaging - Doyle')
      ->update(['status_name' => 'Imaging']);

    DB::table('statuses')
      ->where('status_name', 'Library - Lost')
      ->delete();
    DB::table('statuses')
      ->where('status_name', 'Imaging - Mahoney')
      ->delete();
  }

};
