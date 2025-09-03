<?php

/**
 * @file
 * Update existing devices to use the general pool if they don't have one.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {

  /**
   * Run the migrations.
   */
  public function up(): void {
    if (DB::table('pools')->where('id', 1)->exists()) {
      DB::table('devices')
        ->whereNull('pool_id')
        ->update(['pool_id' => 1]);
    }
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void {
    DB::table('devices')
      ->where('pool_id', 1)
      ->update(['pool_id' => NULL]);
  }

};
