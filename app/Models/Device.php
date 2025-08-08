<?php

namespace App\Models;

use App\Models\Activity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model {
  /** @use HasFactory<\Database\Factories\DeviceFactory> */
  use HasFactory;

  protected $guarded = [
    'id',
    'created_at',
    'updated_at',
  ];

  public function activityLogs() {
    return $this->hasMany(Activity::class);
  }

  public function latestActivity() {
    return $this->hasOne(Activity::class)->latest('date_added');
  }

  /**
   * Find a device by SRJC tag or serial number
   */
  public static function findBySrjcOrSerial($srjcTag = null, $serialNumber = null) {
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
   * Find or create a device by SRJC tag or serial number
    */
  public static function findOrCreateBySrjcOrSerial($srjcTag = null, $serialNumber = null, $modelNumber = null) {
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
