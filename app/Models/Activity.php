<?php

namespace App\Models;

use App\Models\Device;
use App\Models\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model {
  /** @use HasFactory<\Database\Factories\ActivityFactory> */
  use HasFactory;

  protected $guarded = [
    'id',
    'created_at',
    'updated_at',
  ];

  public function device() {
    return $this->belongsTo(Device::class);
  }

  public function status() {
    return $this->belongsTo(Status::class);
  }
}
