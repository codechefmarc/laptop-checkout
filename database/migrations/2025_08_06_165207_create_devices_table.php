<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void {
    Schema::create('devices', function (Blueprint $table) {
      $table->id();
      $table->string('srjc_tag')->nullable();
      $table->string('serial_number')->nullable();
      $table->string('model_number')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void {
    Schema::dropIfExists('devices');
  }
};
