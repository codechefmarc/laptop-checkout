<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\QueryService;

class SearchController extends Controller {
  public function search(Request $request) {

    $hasSearchParams = $request->hasAny(['status_id', 'date_range', 'device_id', 'model_number']);

    $activities = null; // Default to null (no results to show)

    if ($hasSearchParams) {
      $query = QueryService::buildSearchQuery($request);
      $activities = $query->latest('created_at')->paginate(20);
      $activities->appends($request->query());
    }

    return view('search', compact('activities'));
  }

}

