<?php

/**
 * @file
 * Devices database migration.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

  /**
   * Run the migrations.
   */
  public function up(): void {
    Schema::create('walk_in_log', function (Blueprint $table) {
      $table->id();
      $table->string('username')->nullable();
      $table->text('description')->nullable();
      $table->boolean('escalated')->default(FALSE);
      $table->integer('duration_seconds')->nullable();
      $table->timestamps();
    });

    Schema::create('support_category_walk_in_log', function (Blueprint $table) {
      $table->id();
      $table->foreignId('walk_in_log_id')->constrained('walk_in_log')->onDelete('cascade');
      $table->foreignId('support_category_id')->constrained('support_categories')->onDelete('cascade');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void {
    Schema::dropIfExists('support_category_walk_in_log');
    Schema::dropIfExists('walk_in_log');
  }

};
