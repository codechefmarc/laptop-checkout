<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Describes a status and provides status methods.
 */
class Status extends Model {
  use HasFactory;

  /**
   * Statuses have many activities.
   */
  public function activities() {
    return $this->hasMany(Activity::class);
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
   * Get a status name by its ID.
   *
   * @param int $id
   *   The status ID to search for.
   *
   * @return string|null
   *   The status name, or null if not found
   */
  public static function getNameById(int $id): ?string {
    $status = self::find($id);
    return $status?->status_name;
  }

  /**
   * Get a status by its name.
   *
   * @param string $name
   *   The status name to search for.
   *
   * @return Status|null
   *   The status model, or null if not found
   */
  public static function findByName(string $name): ?Status {
    return self::where('status_name', $name)->first();
  }

}
