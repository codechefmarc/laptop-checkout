<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Describes a device and provides device methods.
 */
class Device extends Model {
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
   * Each device has many activities.
   */
  public function activities() {
    return $this->hasMany(Activity::class);
  }

  /**
   * Each device belongs to one pool.
   */
  public function pool() {
    return $this->belongsTo(Pool::class);
  }

  /**
   * Retrieves the latest activity for a given device.
   */
  public function latestActivity() {
    return $this->hasOne(Activity::class)->latest('date_added');
  }

  /**
   * Find a device by SRJC tag or serial number.
   */
  public static function findBySrjcOrSerial($identifier) {
    return self::where('srjc_tag', $identifier)
      ->orWhere('serial_number', $identifier)
      ->first();
  }

}
