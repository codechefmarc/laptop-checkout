<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;

class SearchController extends Controller {
  public function search(Request $request) {
    // Check if this is a search submission (has any search parameters)
    $hasSearchParams = $request->hasAny(['status_id', 'date_range', 'device_id']); // add your search fields

    $activities = null; // Default to null (no results to show)

    if ($hasSearchParams) {
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

      // Execute the query
      $activities = $query->latest('created_at')->paginate(20);
      $activities->appends($request->query());
  }

  return view('search', compact('activities'));
  }

}

