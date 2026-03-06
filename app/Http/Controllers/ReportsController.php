<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Device;
use App\Models\Pool;
use App\Models\Status;
use App\Models\SupportCategory;
use App\Models\WalkInLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Handles report page requests.
 */
class ReportsController extends Controller {

  /**
   * Overall reports page.
   */
  public function checkoutLaptopReports(Request $request) {
    $activities = NULL;
    $devices = NULL;
    $report_title = NULL;
    $active_device_count = $this->getDeviceCounts();
    $surplus_device_count = $this->getDeviceCounts(TRUE);
    $inactive_device_count = Device::whereDoesntHave('activities')->count();
    $surplusId = Status::getIdByName('Surplus');

    if (isset($request['report'])) {

      switch ($request['report']) {
        case 'all_devices':
          $report_title = 'All Devices (' . Device::count() . ")";
          $devices = Device::orderBy('srjc_tag')->paginate(20);
          $devices->appends(['report' => 'all_devices']);
          break;

        case 'active_devices':
          $report_title = 'Active Devices (' . $active_device_count . ")";
          $devices = Device::select('devices.*')
            ->join('activities', 'devices.id', '=', 'activities.device_id')
            ->join(DB::raw('(
              SELECT device_id, MAX(created_at) as max_date
              FROM activities
              GROUP BY device_id
            ) as latest'),
            function ($join) {
              $join->on('activities.device_id', '=', 'latest.device_id')
                ->on('activities.created_at', '=', 'latest.max_date');
            })
            ->where('activities.status_id', '!=', $surplusId)
            ->distinct()
            ->orderBy('devices.srjc_tag')
            ->paginate(20);
          $devices->appends(['report' => 'active_devices']);
          break;

        case 'surplus_devices':
          $report_title = 'Surplus Devices (' . $surplus_device_count . ")";
          $devices = Device::select('devices.*')
            ->join('activities', 'devices.id', '=', 'activities.device_id')
            ->join(DB::raw('(
              SELECT device_id, MAX(created_at) as max_date
              FROM activities
              GROUP BY device_id
            ) as latest'),
            function ($join) {
              $join->on('activities.device_id', '=', 'latest.device_id')
                ->on('activities.created_at', '=', 'latest.max_date');
            })
            ->where('activities.status_id', $surplusId)
            ->distinct()
            ->orderBy('devices.srjc_tag')
            ->paginate(20);
          $devices->appends(['report' => 'surplus_devices']);
          break;

        case 'inactive_devices':
          $report_title = 'Inactive Devices (' . Device::whereDoesntHave('activities')->count() . ")";
          $devices = Device::whereDoesntHave('activities')->orderBy('srjc_tag')->paginate(20);
          $devices->appends(['report' => 'inactive_devices']);
          break;

        default:
          $request['report'] = NULL;
      }

    }

    return view('reports', [
      'activities' => $activities,
      'devices' => $devices,
      'report_title' => $report_title,
      'active_device_count' => $active_device_count,
      'surplus_device_count' => $surplus_device_count,
      'total_device_count' => Device::count(),
      'flagged_device_count' => Device::where('flagged_for_review', TRUE)->count(),
      'status_counts' => $this->getCurrentStatus(),
      'pool_counts' => $this->getDeviceCountsByPool(),
      'pool_counts_current' => $this->getCurrentStatusByPool(),
      'active_device_model_counts' => $this->getDeviceCountsByModel('not_surplus'),
      'surplus_device_model_counts' => $this->getDeviceCountsByModel('surplus'),
      'inactive_device_count' => $inactive_device_count,
      'surplus_status_id' => Status::getIdByName('Surplus'),
    ]);
  }

  /**
   * Gets device counts.
   */
  private function getDeviceCounts($surplus = FALSE) {
    $surplusId = Status::getIdByName('Surplus');

    $deviceCounts = Device::select('devices.model_number', DB::raw('count(DISTINCT devices.id) as device_count'))
      ->join('activities', 'devices.id', '=', 'activities.device_id')
      ->join(DB::raw('(
        SELECT device_id, MAX(created_at) as max_date
        FROM activities
        GROUP BY device_id
      ) as latest'),
      function ($join) {
        $join->on('activities.device_id', '=', 'latest.device_id')
          ->on('activities.created_at', '=', 'latest.max_date');
      });

    if ($surplus) {
      $deviceCounts->where('activities.status_id', $surplusId);
    }
    else {
      $deviceCounts->where('activities.status_id', '!=', $surplusId);
    }

    $count = $deviceCounts->distinct()
      ->count();

    return $count;
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

  /**
   * Gets device counts by model number filtered by status.
   *
   * @param string $filter
   *   Either 'surplus', 'not_surplus', or 'all'.
   *
   * @return \Illuminate\Support\Collection
   *   A collection of objects with model_number and device_count properties.
   */
  private function getDeviceCountsByModel($filter = 'all') {
    $query = Device::select('devices.model_number', DB::raw('count(DISTINCT devices.id) as device_count'))
      ->join('activities', 'devices.id', '=', 'activities.device_id')
      ->join(DB::raw('(
        SELECT device_id, MAX(created_at) as max_date
        FROM activities
        GROUP BY device_id
      ) as latest'),
      function ($join) {
        $join->on('activities.device_id', '=', 'latest.device_id')
          ->on('activities.created_at', '=', 'latest.max_date');
      });

    // Filter by status.
    $surplusId = Status::getIdByName('Surplus');

    if ($filter === 'surplus' && $surplusId) {
      $query->where('activities.status_id', $surplusId);
    }
    elseif ($filter === 'not_surplus' && $surplusId) {
      $query->where('activities.status_id', '!=', $surplusId);
    }

    $modelCounts = $query
      ->groupBy('devices.model_number')
      ->get()
      ->map(function ($item) {
        return (object) [
          'model_number' => $item->model_number ?? 'Unknown Model',
          'device_count' => $item->device_count,
        ];
      })
      ->sortByDesc('device_count')
      ->values();

    return $modelCounts;
  }

  /**
   * Walk-in log report.
   */
  public function walkInLog(Request $request) {
    $categories = SupportCategory::orderBy('weight')->orderBy('name')->get();

    $selectedCategory = $request->input('category_id');
    $escalated = $request->input('escalated');
    $dateRange = $request->input('date_range');

    $startDate = NULL;
    $endDate = NULL;

    if ($dateRange) {
      $parts = explode(' to ', $dateRange);
      $startDate = $parts[0];
      $endDate = $parts[1] ?? $parts[0];
    }

    $query = WalkInLog::query();

    if ($startDate && $endDate) {
      $query->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
    }

    if ($escalated === '1') {
      $query->where('escalated', TRUE);
    }

    // Total before category filter (for escalation percentage)
    $totalCount = (clone $query)->count();
    $totalEscalated = (clone $query)->where('escalated', TRUE)->count();

    if ($selectedCategory) {
      $categoryQuery = (clone $query)
        ->whereHas('supportCategories', fn($q) => $q->where('support_categories.id', $selectedCategory));

      $counts = collect([
        [
          'name'         => $categories->firstWhere('id', $selectedCategory)?->name,
          'count'        => (clone $categoryQuery)->count(),
          'avg_duration' => (clone $categoryQuery)->avg('duration_minutes'),
        ],
      ]);
    }
    else {
      $counts = $categories->map(function ($category) use ($query) {
        $categoryQuery = (clone $query)
          ->whereHas('supportCategories', fn($q) => $q->where('support_categories.id', $category->id));

        return [
          'name'             => $category->name,
          'count'            => (clone $categoryQuery)->count(),
          'avg_duration'     => (clone $categoryQuery)->avg('duration_minutes'),
        ];
      })
        ->filter(fn($row) => $row['count'] > 0)
        ->sortByDesc('count')
        ->values();
    }

    return view('reports.walk-in-report', [
      'counts'           => $counts,
      'totalCount'       => $totalCount,
      'totalEscalated'   => $totalEscalated,
      'categories'       => $categories,
      'selectedCategory' => $selectedCategory,
      'escalated'        => $escalated,
      'dateRange'        => $dateRange,
    ]);
  }

}
