<?php

namespace App\Http\Controllers;

use App\Services\QueryService;
use Illuminate\Http\Request;

/**
 * Handles search page.
 */
class SearchController extends Controller {

  /**
   * Main search form.
   */
  public function search(Request $request) {

    $hasSearchParams = $request->hasAny(['status_id', 'date_range', 'device_id', 'model_number']);

    $activities = NULL;

    if ($hasSearchParams) {
      $query = QueryService::buildSearchQuery($request);
      $activities = $query->latest('created_at')->paginate(20);
      $activities->appends($request->query());
    }

    return view('search', compact('activities'));
  }

}
