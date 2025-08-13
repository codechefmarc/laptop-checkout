<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\Activity;
use Illuminate\Support\Facades\DB;

class QueryService {
  public static function buildSearchQuery(Request $request) {
    // Start with a base query
    $query = Activity::with(['device', 'status']);

    // Handle Status Filter
    if ($request->filled('status_id') && $request->status_id !== 'any') {
      $query->where('status_id', $request->status_id);
    }

    // Handle SRJC Filter
    if ($request->filled('srjc_tag')) {
      $query->whereHas('device', function ($q) use ($request) {
        $q->where('srjc_tag', $request->srjc_tag);
      });
    }

    // Handle Serial Number
    if ($request->filled('serial_number')) {
      $query->whereHas('device', function ($q) use ($request) {
        $q->where('serial_number', $request->serial_number);
      });
    }

    // Handle Model Number
    if ($request->filled('model_number')) {
      $query->whereHas('device', function ($q) use ($request) {
        $q->where('model_number', 'LIKE', '%' . $request->model_number . '%');
      });
    }

    // Handle Date Filter
    if ($request->filled('date_range')) {
      $dateInput = $request->date_range;

      // Check if it's a date range (contains " to ")
      if (str_contains($dateInput, ' to ')) {
        // It's a date range: "2025-08-11 to 2025-08-12"
        $dateRange = explode(' to ', $dateInput);
        $startDate = trim($dateRange[0]);
        $endDate = trim($dateRange[1]);

        // Actually USE the variables in the query!
        $query->whereBetween('created_at', [
          $startDate . ' 00:00:00',
          $endDate . ' 23:59:59'
        ]);
      }
      else {
        // Single date or comma-separated dates
        if (str_contains($dateInput, ',')) {
          // Multiple individual dates
          $selectedDates = array_map('trim', explode(',', $dateInput));
          $query->where(function ($q) use ($selectedDates) {
            foreach ($selectedDates as $date) {
              $q->orWhereDate('created_at', $date);
            }
          });
        }
        else {
          // Single date
          $query->whereDate('created_at', trim($dateInput));
        }
      }
    }

    if ($request->has('current_status_only')) {
      $query->whereIn(DB::raw('(device_id, created_at)'), function($subquery) {
        $subquery->select('device_id', DB::raw('MAX(created_at)'))
          ->from('activities')
          ->groupBy('device_id');
      });
    }
    return $query;
  }

}
