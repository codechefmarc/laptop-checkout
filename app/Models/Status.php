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

}
