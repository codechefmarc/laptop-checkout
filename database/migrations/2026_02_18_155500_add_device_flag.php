<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Add flag column to the devices table for the "lost and paid" review workflow.
 */
return new class extends Migration {

  /**
   * Add 'flagged_for_review' and 'flag_note' columns to the devices table.
   */
  public function up(): void {
    Schema::table('devices', function (Blueprint $table) {
      $table->boolean('flagged_for_review')->default(FALSE)->after('pool_id');
      $table->text('flag_note')->nullable()->after('flagged_for_review');
    });
  }

  /**
   * Remove the added columns on rollback.
   */
  public function down(): void {
    Schema::table('devices', function (Blueprint $table) {
        $table->dropColumn(['flagged_for_review', 'flag_note']);
    });
  }

};
