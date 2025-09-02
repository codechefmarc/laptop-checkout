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

    $hasSearchParams = $request->hasAny(['status_id', 'date_range', 'srjc_tag', 'serial_number', 'model_number']);

    // With device search, only return results if no status or date is set.
    $hasDeviceSearchParams =
      $request->hasAny(['srjc_tag', 'serial_number', 'model_number'])
      && $request->string('status_id') == 'any'
      && !$request->filled('date_range');

    $activities = NULL;

    if ($hasSearchParams) {
      $query = QueryService::buildSearchQuery($request);
      $activities = $query->latest('created_at')->paginate(20);
      $activities->appends($request->query());
    }

    $devices = NULL;

    if ($hasDeviceSearchParams) {
      $query = QueryService::buildDeviceSearchQuery($request);
      $devices = $query->orderBy('srjc_tag')->paginate(20);
      $devices->appends($request->query());
    }

    return view('search', compact('activities', 'devices'));
  }

}
