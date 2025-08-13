<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use App\Models\Device;
use Illuminate\Support\Facades\DB;
use App\Models\Status;

class ReportsController extends Controller {
  public function reports(Request $request) {
    $activities = null;
    $devices = null;
    $report_title = null;
    $device_count = Device::count();
    $status_counts = $this->getCurrentStatus();

    if (isset($request['report'])) {
      if ($request['report'] == 'all_devices') {
        $report_title = 'All Devices';
        $devices = Device::latest('updated_at')->paginate(20);
      }
      if ($request['report'] == 'inactive_devices') {
        $report_title = 'Inactive Devices';
        $devices = Device::whereDoesntHave('activities')->latest('updated_at')->paginate(20);
      }
      else {
        $report_title = null;
      }
    }

    return view('reports', [
      'activities' => $activities,
      'devices' => $devices,
      'report_title' => $report_title,
      'device_count' => $device_count,
      'status_counts' => $status_counts,
    ]);
  }

  private function getCurrentStatus() {
    $latestActivities = Activity::select('activities.device_id', 'activities.status_id')
    ->join(DB::raw('(
        SELECT device_id, MAX(created_at) as max_date
        FROM activities
        GROUP BY device_id
    ) as latest'), function ($join) {
        $join->on('activities.device_id', '=', 'latest.device_id')
             ->on('activities.created_at', '=', 'latest.max_date');
    })
    ->get();

    // Step 2: Count by status
    $statusCounts = $latestActivities
    ->groupBy('status_id')
    ->map(function($group) {
        $status = Status::find($group->first()->status_id);
        return (object)[
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

}
