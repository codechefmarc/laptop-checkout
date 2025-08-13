<?php

namespace App\Models;

use App\Models\Activity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model {
  /** @use HasFactory<\Database\Factories\StatusFactory> */
  use HasFactory;

  public function activities() {
    return $this->hasMany(Activity::class);
  }

}
