<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Describes an activity and provides activity methods.
 */
class Activity extends Model {
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
   * Each activity belongs to one device.
   */
  public function device() {
    return $this->belongsTo(Device::class);
  }

  /**
   * Each activity belongs to one status.
   */
  public function status() {
    return $this->belongsTo(Status::class);
  }

}
