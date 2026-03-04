<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Describes a support category and provides related methods.
 */
class SupportCategory extends Model {
  /**
   * The table associated with the model.
   *
   * @var table
   */
  protected $table = 'support_categories';

  /**
   * The attributes that are mass assignable.
   *
   * @var fillable
   */
  protected $fillable = [
    'name',
    'description',
  ];

  /**
   * Each walk-in log entry can belong to many support categories.
   */
  public function walkInLogs() {
    return $this->belongsToMany(WalkInLog::class, 'support_category_walk_in_log', 'support_category_id', 'walk_in_log_id');
  }

}
