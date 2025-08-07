<?php

namespace App\Models;

use App\Models\Activity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model {
  /** @use HasFactory<\Database\Factories\DeviceFactory> */
  use HasFactory;

  public function activityLogs() {
    return $this->hasMany(Activity::class);
  }

  public function latestActivity() {
    return $this->hasOne(Activity::class)->latest('date_added');
  }
}
