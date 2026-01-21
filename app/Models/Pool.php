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

  /**
   * Get a status ID by its name.
   *
   * @param string $name
   *   The status name to search for.
   *
   * @return int|null
   *   The status ID, or null if not found
   */
  public static function getIdByName(string $name): ?int {
    $status = self::where('status_name', $name)->first();
    return $status?->id;
  }

  /**
   * Get a pool name by its ID.
   *
   * @param int $id
   *   The pool ID to search for.
   *
   * @return string|null
   *   The status name, or null if not found
   */
  public static function getNameById(int $id): ?string {
    $pool = self::find($id);
    return $pool?->status_name;
  }

  /**
   * Get a pool by its name.
   *
   * @param string $name
   *   The pool name to search for.
   *
   * @return Pool|null
   *   The pool model, or null if not found
   */
  public static function findByName(string $name): ?Status {
    return self::where('name', $name)->first();
  }

}
