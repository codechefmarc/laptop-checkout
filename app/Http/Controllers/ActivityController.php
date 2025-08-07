<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller {

  public function logActivity() {
    $activities = Activity::with(['device', 'status'])
      ->whereDate('created_at', today())
      ->latest('created_at')
      ->simplePaginate(20);
    return view('activities.today', compact('activities'));
  }
}
