<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Describes a pool and provides pool methods.
 */
class Pool extends Model {
  use HasFactory;

  /**
   * Protected fields from editing on the front-end.
   *
   * @var guarded
   */
  protected $guarded = [
    'id',
    'created_at',
    'updated_at',
  ];

  /**
   * Each pool has many devices.
   */
  public function devices() {
    return $this->hasMany(Device::class);
  }

}
