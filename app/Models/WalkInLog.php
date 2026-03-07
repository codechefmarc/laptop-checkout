<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Describes a walk-in log entry and provides related methods.
 */
class WalkInLog extends Model {
  use HasFactory;
  /**
   * The table associated with the model.
   *
   * @var table
   */
  protected $table = 'walk_in_log';

  /**
   * The attributes that are mass assignable.
   *
   * @var fillable
   */
  protected $fillable = [
    'username',
    'description',
    'escalated',
    'duration_minutes',
    'resolved',
  ];

  /**
   * Each walk-in log entry can belong to many support categories.
   */
  public function supportCategories() {
    return $this->belongsToMany(SupportCategory::class, 'support_category_walk_in_log', 'walk_in_log_id', 'support_category_id');
  }

}
