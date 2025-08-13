<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;

class ReportsController extends Controller {
  public function reports(Request $request) {
    $activities = null;
    return view('reports', compact('activities'));
  }

}

