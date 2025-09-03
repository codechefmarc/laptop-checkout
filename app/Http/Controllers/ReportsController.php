<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Device;
use App\Models\Pool;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Handles report page requests.
 */
class ReportsController extends Controller {

  /**
   * Overall reports page.
   */
  public function reports(Request $request) {
    $activities = NULL;
    $devices = NULL;
    $report_title = NULL;
    $device_count = Device::count();
    $inactive_count = Device::whereDoesntHave('activities')->count();

    if (isset($request['report'])) {

      switch ($request['report']) {
        case 'all_devices':
          $report_title = 'All Devices (' . Device::count() . ")";
          $devices = Device::orderBy('srjc_tag')->paginate(20);
          $devices->appends(['report' => 'all_devices']);
          break;

        case 'inactive_devices':
          $report_title = 'Inactive Devices (' . Device::whereDoesntHave('activities')->count() . ")";
          $devices = Device::whereDoesntHave('activities')->orderBy('srjc_tag')->paginate(20);
          $devices->appends(['report' => 'inactive_devices']);
          break;

        case 'devices_by_pool':
          if (isset($request['pool_id']) && is_numeric($request['pool_id'])) {
            $pool = Pool::find($request['pool_id']);
            if ($pool) {
              $report_title = 'Devices in ' . $pool->name . ' (' . Device::where('pool_id', $pool->id)->count() . ")";
              $devices = Device::where('pool_id', $pool->id)->orderBy('srjc_tag')->paginate(20);
              $devices->appends(['report' => 'devices_by_pool', 'pool_id' => $pool->id]);
            } else {
              $report_title = 'Invalid Pool ID';
              $devices = NULL;
            }
          } else {
            $report_title = 'No Pool ID Specified';
            $devices = NULL;
          }

          break;

        default:
          $request['report'] = NULL;
      }

    }

    return view('reports', [
      'activities' => $activities,
      'devices' => $devices,
      'report_title' => $report_title,
      'device_count' => $device_count,
      'inactive_count' => $inactive_count,
      'status_counts' => $this->getCurrentStatus(),
      'pool_counts' => $this->getDeviceCountsByPool(),
      'pool_counts_current' => $this->getCurrentStatusByPool(),
    ]);
  }

  /**
   * Gets current status of devices.
   */
  private function getCurrentStatus() {
    $latestActivities = Activity::select('activities.device_id', 'activities.status_id')
      ->join(DB::raw('(
        SELECT device_id, MAX(created_at) as max_date
        FROM activities
        GROUP BY device_id
        ) as latest'),
        function ($join) {
          $join->on('activities.device_id', '=', 'latest.device_id')
            ->on('activities.created_at', '=', 'latest.max_date');
        })->get();

    $statusCounts = $latestActivities
      ->groupBy('status_id')
      ->map(function ($group) {
        $status = Status::find($group->first()->status_id);
        return (object) [
          'status_id' => $status->id,
          'status_name' => $status->status_name,
          'description' => $status->description,
          'device_count' => $group->count(),
        ];
      })
      ->sortByDesc('device_count')
      ->values();

    return $statusCounts;
  }

  /**
   * Gets current status of devices grouped by pool.
   */
  private function getCurrentStatusByPool() {
    $latestActivities = Activity::select('activities.device_id', 'activities.status_id', 'devices.pool_id')
      ->join('devices', 'activities.device_id', '=', 'devices.id')
      ->join(DB::raw('(
          SELECT device_id, MAX(created_at) as max_date
          FROM activities
          GROUP BY device_id
      ) as latest'),
      function ($join) {
          $join->on('activities.device_id', '=', 'latest.device_id')
            ->on('activities.created_at', '=', 'latest.max_date');
      })->get();

    $poolCounts = $latestActivities
      ->groupBy('pool_id')
      ->map(function ($group) {
        $pool = Pool::find($group->first()->pool_id);
        return (object) [
          'pool_id' => $pool?->id,
          'pool_name' => $pool?->name ?? 'No Pool Assigned',
          'device_count' => $group->count(),
          'status_breakdown' => $group->groupBy('status_id')->map->count(),
        ];
      })
      ->sortByDesc('device_count')
      ->values();

    return $poolCounts;
  }

  /**
   * Gets device counts by pool.
   */
  private function getDeviceCountsByPool() {
    $poolCounts = Device::select('pool_id', DB::raw('count(*) as device_count'))
      ->groupBy('pool_id')
      ->with('pool')
      ->get()
      ->map(function ($item) {
        return (object) [
          'pool_id' => $item->pool_id,
          'pool_name' => $item->pool?->name ?? 'No Pool Assigned',
          'device_count' => $item->device_count,
        ];
      })
      ->sortByDesc('device_count')
      ->values();

    return $poolCounts;
  }

}
