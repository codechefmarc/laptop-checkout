<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Services\QueryService;
use Illuminate\Http\Request;
/**
 * Handles export to CSV.
 */

class ExportController extends Controller {

  /**
   * Handles export activities to CSV.
   */
  public function activities(Request $request) {
    $query = QueryService::buildSearchQuery($request);
    $activities = $query->latest('created_at')->get();

    $filename = 'activities_' . now()->format('Y-m-d_H-i-s') . '.csv';

    $headers = [
      'Content-Type' => 'text/csv',
      'Content-Disposition' => 'attachment; filename="' . $filename . '"',
    ];

    return response()->stream(function () use ($activities) {
      $file = fopen('php://output', 'w');

      // CSV Header.
      fputcsv($file, [
        'Date', 'Time', 'SRJC Tag', 'Serial Number',
        'Model Number', 'Status', 'User', 'Notes',
      ]);

      // CSV Data.
      foreach ($activities as $activity) {
        fputcsv($file, [
          $activity->created_at->format('m/d/Y'),
          $activity->created_at->format('g:iA'),
          $activity->device->srjc_tag ?? '',
          $activity->device->serial_number ?? '',
          $activity->device->model_number ?? '',
          $activity->status->status_name ?? '',
          $activity->username ?? '',
          $activity->notes ?? '',
        ]);
      }

      fclose($file);
    }, 200, $headers);
  }

  /**
   * Handles export of all devices.
   */
  public function devices(Request $request) {

    $query = Device::query();

    if ($request->get('report') === 'inactive_devices') {
      $query->whereDoesntHave('activities');
      $filename = 'inactive_devices_' . now()->format('Y-m-d_H-i-s') . '.csv';
    }
    else {
      $filename = 'all_devices_' . now()->format('Y-m-d_H-i-s') . '.csv';
    }

    $devices = $query->latest('updated_at')->get();

    $headers = [
      'Content-Type' => 'text/csv',
      'Content-Disposition' => 'attachment; filename="' . $filename . '"',
    ];

    return response()->stream(function () use ($devices) {
      $file = fopen('php://output', 'w');

      // CSV Header
      fputcsv($file, [
        'Date Updated', 'SRJC Tag', 'Serial Number', 'Model Number',
      ]);

      // CSV Data
      foreach ($devices as $device) {
        fputcsv($file, [
          $device->updated_at->format('m/d/Y'),
          $device->srjc_tag ?? '',
          $device->serial_number ?? '',
          $device->model_number ?? '',
        ]);
      }

      fclose($file);
    }, 200, $headers);
  }

  /**
   * Handles export of all devices.
   */
  public function inactiveDevices() {

    $devices = Device::whereDoesntHave('activities')->get();

    $filename = 'devices_' . now()->format('Y-m-d_H-i-s') . '.csv';

    $headers = [
      'Content-Type' => 'text/csv',
      'Content-Disposition' => 'attachment; filename="' . $filename . '"',
    ];

    return response()->stream(function () use ($devices) {
      $file = fopen('php://output', 'w');

      // CSV Header.
      fputcsv($file, [
        'Date Updated', 'SRJC Tag', 'Serial Number', 'Model Number',
      ]);

      // CSV Data
      foreach ($devices as $device) {
        fputcsv($file, [
          $device->created_at->format('m/d/Y'),
          $device->srjc_tag ?? '',
          $device->serial_number ?? '',
          $device->model_number ?? '',
        ]);
      }

      fclose($file);
    }, 200, $headers);
  }

}
