<?php

namespace App\Http\Controllers;

use App\Models\SupportCategory;
use App\Models\WalkInLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

/**
 * Controller for Walk In Log related routing.
 */
class WalkInLogController extends Controller {

  /**
   * Show the form for logging walk in activity.
   */
  public function walkInLog() {
    $supportCategories = SupportCategory::orderBy('weight', 'asc')->get();
    $activeWalkIns = WalkInLog::with('supportCategories')
      ->whereDate('created_at', today())
      ->where('duration_minutes', NULL)
      ->latest('updated_at')
      ->paginate(20);
    $completedWalkIns = WalkInLog::with('supportCategories')
      ->whereDate('created_at', today())
      ->whereNotNull('duration_minutes')
      ->latest('updated_at')
      ->paginate(20);
    $activeWalkInsCurrentUser = $activeWalkIns->where('username', Auth::user()->first_name . ' ' . Auth::user()->last_name)->count();
    return view('components.walk-in.index', compact('supportCategories', 'activeWalkIns', 'completedWalkIns', 'activeWalkInsCurrentUser'));
  }

  /**
   * Store the walk in log activity.
   */
  public function storeWalkIn(Request $request) {
    $validated = $request->validate([
      'description' => 'nullable|string',
      'support_category_id' => [
        ['required', 'array', 'min:1'],
      ],
      'support_category_id.*' => [Rule::exists('support_categories', 'id')],
      'escalate' => ['nullable', Rule::in(['yes', 'no'])],
      'duration_minutes' => ['nullable', 'integer', 'min:1'],
    ], [
      'support_category_id.required' => 'Select at least one support category.',
    ]);

    WalkInLog::create([
      'username' => Auth::user()->first_name . ' ' . Auth::user()->last_name,
      'description' => $validated['description'] ?? NULL,
      'escalated' => ($validated['escalate'] ?? 'no') === 'yes',
      'duration_minutes' => $validated['duration_minutes'] ?? NULL,
    ])->supportCategories()->attach($validated['support_category_id']);

    return redirect()->route('walk_in_log.index')->with('success', 'Walk in successfully logged.');
  }

  /**
   * Edit walk in log entry.
   */
  public function edit(WalkInLog $walkIn) {
    $returnUrl = url()->previous();
    return view('components.walk-in.edit', [
      'walkIn' => $walkIn,
      'supportCategories' => SupportCategory::orderBy('weight', 'asc')->get(),
      'returnUrl' => $returnUrl,
    ]);
  }

  /**
   * Update the specified walk in log entry.
   */
  public function patch(Request $request, WalkInLog $walkIn) {
    $validated = $request->validate([
      'description' => 'nullable|string',
      'support_category_id' => [
        ['required', 'array', 'min:1'],
      ],
      'support_category_id.*' => [Rule::exists('support_categories', 'id')],
      'escalate' => ['nullable', Rule::in(['yes', 'no'])],
      'duration_minutes' => ['nullable', 'integer', 'min:1'],
    ], [
      'support_category_id.required' => 'Select at least one support category.',
    ]);
    $walkIn->update([
      'description' => $validated['description'] ?? NULL,
      'escalated' => ($validated['escalate'] ?? 'no') === 'yes',
      'duration_minutes' => $validated['duration_minutes'] ?? NULL,
      'updated_at' => now(),
    ]);
    $walkIn->supportCategories()->sync($validated['support_category_id']);

    return redirect()->route('walk_in_log.index')->with('success', 'Walk in log entry successfully updated.');
  }

  /**
   * Complete the specified walk in without editing first.
   */
  public function complete(WalkInLog $walkIn) {
    $walkIn->update([
      'duration_minutes' => $walkIn->created_at->diffInMinutes(now()),
    ]);

    return redirect()->route('walk_in_log.index')->with('success', 'Walk in completed.');
  }

}
