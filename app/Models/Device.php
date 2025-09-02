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
   * Each device has one pool.
   */
  public function pool() {
    return $this->hasOne(Activity::class);
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
  public static function findBySrjcOrSerial($srjcTag = NULL, $serialNumber = NULL) {
    return self::where(function ($query) use ($srjcTag, $serialNumber) {
      if ($srjcTag) {
          $query->orWhere('srjc_tag', $srjcTag);
      }
      if ($serialNumber) {
          $query->orWhere('serial_number', $serialNumber);
      }
    })->first();
  }

  /**
   * Find or create a device by SRJC tag or serial number.
   */
  public static function findOrCreateBySrjcOrSerial($srjcTag = NULL, $serialNumber = NULL, $modelNumber = NULL) {
    $device = self::findBySrjcOrSerial($srjcTag, $serialNumber);

    if (!$device) {
      $device = self::create([
        'srjc_tag' => $srjcTag,
        'serial_number' => $serialNumber,
        'model_number' => $modelNumber,
      ]);
    }

    return $device;
  }

}
