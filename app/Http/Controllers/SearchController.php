<?php

namespace App\Http\Controllers;

use App\Models\Pool;
use App\Models\Status;
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

    if ($request->input('exclude_surplus') === 'on') {
      $request->merge(['status_id' => 'not_surplus']);
    }

    $hasSearchParams = $request->hasAny(
      [
        'status_id',
        'date_range',
        'srjc_tag',
        'serial_number',
        'model_number',
        'pool_id',
        'notes',
      ]
    );

    // With device search, only return results if no status or date is set.
    $hasDeviceSearchParams =
      $request->hasAny(['srjc_tag', 'serial_number', 'model_number', 'pool_id'])
      && $request->string('status_id') == 'any'
      && !$request->filled('date_range')
      && $request->string('notes')->isEmpty();

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

    // Prepare status filter info for display.
    $statusFilterInfo = $this->getStatusFilterInfo($request);
    $poolName = $this->getPoolName($request);

    return view('search', compact('activities', 'devices', 'statusFilterInfo', 'poolName'));
  }

  /**
   * Get status filter information for display.
   */
  private function getStatusFilterInfo(Request $request) {
    $statusId = $request->input('status_id');

    if (!$statusId || $statusId === 'any') {
      return NULL;
    }

    // Check for exclusion filter.
    if (str_starts_with($statusId, 'not_')) {
      $statusName = ucfirst(substr($statusId, 4));
      return [
        'type' => 'exclusion',
        'name' => $statusName,
      ];
    }

    // Check for multiple statuses.
    if (str_contains($statusId, ',')) {
      $statusIds = array_map('trim', explode(',', $statusId));
      $statusNames = [];
      foreach ($statusIds as $id) {
        $name = Status::getNameById((int) $id);
        if ($name) {
          $statusNames[] = $name;
        }
      }
      return [
        'type' => 'multiple',
        'names' => $statusNames,
        'count' => count($statusNames),
      ];
    }

    // Single status.
    $statusName = Status::getNameById((int) $statusId);
    if ($statusName) {
      return [
        'type' => 'single',
        'name' => $statusName,
      ];
    }

    return NULL;
  }

  /**
   * Get pool name for display.
   */
  private function getPoolName(Request $request) {
    $poolId = $request->input('pool_id');

    if (!$poolId || $poolId === 'any') {
      return NULL;
    }

    $pool = Pool::find((int) $poolId);
    return $pool ? $pool->name : NULL;
  }

}
