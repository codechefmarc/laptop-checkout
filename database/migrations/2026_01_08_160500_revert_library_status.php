<?php

/**
 * @file
 * Revert back to Library status from Doyle and Mahoney.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {

  /**
   * Run the migrations.
   */
  public function up(): void {
    // First, get the IDs for both statuses.
    $mahoneyStatus = DB::table('statuses')->where('status_name', 'Mahoney')->first();
    $doyleStatus = DB::table('statuses')->where('status_name', 'Doyle')->first();

    // Migrate all activities from Mahoney and Doyle to Library.
    if ($mahoneyStatus && $doyleStatus) {
      DB::table('activities')
        ->where('status_id', $mahoneyStatus->id)
        ->update(['status_id' => $doyleStatus->id]);
    }

    // Delete the Mahoney status.
    DB::table('statuses')->where('status_name', 'Mahoney')->delete();

    // Revert Doyle back to Library.
    DB::table('statuses')->where('status_name', 'Doyle')->update([
      'status_name' => 'Library',
    ]);
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void {
    // Recreate Mahoney status.
    DB::table('statuses')->insert([
      'status_name' => 'Mahoney',
      'tailwind_class' => 'bg-stone-500',
      'weight' => 200,
    ]);

    // Revert Library back to Doyle.
    DB::table('statuses')->where('status_name', 'Library')->update([
      'status_name' => 'Doyle',
    ]);

  }

};
