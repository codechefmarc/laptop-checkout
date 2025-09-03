<?php

namespace App\Services;

use App\Models\Activity;
use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Provides a shared query for search and export.
 */
class QueryService {

  /**
   * Builds a query based on requests for search and export.
   */
  public static function buildSearchQuery(Request $request) {

    // Start with a base query.
    $query = Activity::with(['device', 'status']);

    // Handle Status Filter.
    if ($request->filled('status_id') && $request->status_id !== 'any') {
      $query->where('status_id', $request->status_id);
    }

    // Handle SRJC Filter.
    if ($request->filled('srjc_tag')) {
      $query->whereHas('device', function ($q) use ($request) {
        $q->where('srjc_tag', $request->srjc_tag);
      });
    }

    // Handle Serial Number.
    if ($request->filled('serial_number')) {
      $query->whereHas('device', function ($q) use ($request) {
        $q->where('serial_number', $request->serial_number);
      });
    }

    // Handle Model Number.
    if ($request->filled('model_number')) {
      $query->whereHas('device', function ($q) use ($request) {
        $q->where('model_number', 'LIKE', '%' . $request->model_number . '%');
      });
    }

    // Handle Date Filter.
    if ($request->filled('date_range')) {
      $dateInput = $request->date_range;

      // Date range.
      if (str_contains($dateInput, ' to ')) {
        $dateRange = explode(' to ', $dateInput);
        $startDate = trim($dateRange[0]);
        $endDate = trim($dateRange[1]);

        $query->whereBetween('created_at', [
          $startDate . ' 00:00:00',
          $endDate . ' 23:59:59',
        ]);
      }
      else {
        if (str_contains($dateInput, ',')) {
          // Multiple individual dates.
          $selectedDates = array_map('trim', explode(',', $dateInput));
          $query->where(function ($q) use ($selectedDates) {
            foreach ($selectedDates as $date) {
              $q->orWhereDate('created_at', $date);
            }
          });
        }
        else {
          // Single date.
          $query->whereDate('created_at', trim($dateInput));
        }
      }
    }

    // Handle Pool Filter.
    if ($request->filled('pool_id')) {
      $query->whereHas('device', function ($q) use ($request) {
        $q->where('pool_id', $request->pool_id);
      });
    }

    if ($request->has('current_status_only')) {
      $query->whereIn(DB::raw('(device_id, created_at)'), function ($subquery) {
        $subquery->select('device_id', DB::raw('MAX(created_at)'))
          ->from('activities')
          ->groupBy('device_id');
      });
    }
    return $query;
  }

  /**
   * Builds a query based on requests for search and export.
   */
  public static function buildDeviceSearchQuery(Request $request) {

    // Start with a base query.
    $query = Device::query();

    // Handle SRJC Filter.
    if ($request->filled('srjc_tag')) {
      $query->where('srjc_tag', $request->srjc_tag);
    }

    // Handle Serial Number.
    if ($request->filled('serial_number')) {
      $query->where('serial_number', $request->serial_number);
    }

    // Handle Model Number.
    if ($request->filled('model_number')) {
      $query->where('model_number', 'LIKE', '%' . $request->model_number . '%');
    }

    // Handle Pool Filter.
    if ($request->filled('pool_id')) {
      $query->where('pool_id', $request->pool_id);
    }

    return $query;
  }

}
