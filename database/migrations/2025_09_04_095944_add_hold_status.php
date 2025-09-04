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
      ->insert([
        'status_name' => 'Hold',
        'tailwind_class' => 'bg-stone-500',
      ]);
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void {
    DB::table('statuses')
      ->where('status_name', 'Hold')
      ->delete();
  }

};
