<?php

/**
 * @file
 * Add status and order by to statuses table.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

  /**
   * Run the migrations.
   */
  public function up(): void {
    Schema::table('statuses', function (Blueprint $table) {
      $table->integer('weight')->default(0);
    });

    DB::table('statuses')->where('status_name', 'Library')->update([
      'status_name' => 'Doyle',
    ]);

    DB::table('statuses')
      ->insert([
        'status_name' => 'Mahoney',
        'tailwind_class' => 'bg-stone-500',
        'weight' => 200,
      ]);

    $updates = [
      'Doyle' => 100,
      'Imaging' => 300,
      'Surplus' => 400,
      'Repair' => 500,
      'Hold' => 600,
    ];

    foreach ($updates as $name => $weight) {
      DB::table('statuses')->where('status_name', $name)->update(['weight' => $weight]);
    }

  }

  /**
   * Reverse the migrations.
   */
  public function down(): void {
    Schema::table('statuses', function (Blueprint $table) {
      $table->dropColumn('weight');
    });

    DB::table('statuses')->where('status_name', 'Mahoney')->delete();
  }

};
