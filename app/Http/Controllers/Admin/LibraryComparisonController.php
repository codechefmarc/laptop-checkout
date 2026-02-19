<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Device;
use App\Models\Pool;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Controller for the Library Comparison Tool.
 *
 * This tool allows admins to compare a list of devices from the library export
 * against our internal database, identify matches/mismatches, and take actions.
 */
class LibraryComparisonController extends Controller {

  /**
   * Show the comparison tool form.
   */
  public function index() {
    $statuses         = Status::orderBy('weight')->get();
    $pools            = Pool::orderBy('name')->get();
    $models           = Device::select('model_number')->distinct()->orderBy('model_number')->pluck('model_number');
    $incomingStatuses = $this->incomingStatusOptions();
    $results          = session('lc_results');

    return view('admin.library-comparison.index', compact(
        'statuses', 'pools', 'incomingStatuses', 'models', 'results'
    ))->with([
      'last_identifiers'     => session('lc_identifiers'),
      'last_identifier_type' => session('lc_identifier_type'),
      'last_incoming_status' => session('lc_incoming_status'),
    ]);
  }

  /**
   * Run the comparison and return results.
   */
  public function compare(Request $request) {
    $request->validate([
      'identifiers'     => 'required|string',
      'identifier_type' => 'required|in:srjc_tag,serial_number',
      'incoming_status' => 'required|string',
    ]);

    session([
      'lc_identifiers'     => $request->identifiers,
      'lc_identifier_type' => $request->identifier_type,
      'lc_incoming_status' => $request->incoming_status,
    ]);

    return $this->runComparison($request->identifiers, $request->identifier_type, $request->incoming_status);
  }

  /**
   * Re-run comparison with last input (after taking actions).
   */
  public function reCompare() {
    if (!session()->has('lc_identifiers')) {
      return redirect()->route('admin.library_comparison.index');
    }

    return $this->runComparison(
      session('lc_identifiers'),
      session('lc_identifier_type'),
      session('lc_incoming_status')
    );
  }

  /**
   * Run the comparison logic.
   */
  private function runComparison(string $identifiers, string $identifierType, string $incomingLabel): \Illuminate\Http\RedirectResponse {
    $identifierList = collect(explode("\n", $identifiers))
      ->map(fn($line) => trim($line))
      ->filter()->unique()->values();

    $mapping      = $this->resolveMapping($incomingLabel);
    $mappedStatus = isset($mapping['status'])
        ? Status::where('status_name', $mapping['status'])->first()
        : NULL;
    $isDeleteFlag = $mapping['delete_flag'] ?? FALSE;

    $results = [];

    foreach ($identifierList as $identifier) {
      $device = $identifierType === 'srjc_tag'
          ? Device::where('srjc_tag', $identifier)->first()
          : Device::where('serial_number', $identifier)->first();

      if (!$device) {
        $results[] = [
          'identifier'      => $identifier,
          'identifier_type' => $identifierType,
          'device'          => NULL,
          'current_status'  => NULL,
          'mapped_status'   => $mappedStatus,
          'delete_flag'     => $isDeleteFlag,
          'result_type'     => 'not_found',
        ];
        continue;
      }

      $latestActivity = Activity::where('device_id', $device->id)
        ->with('status')->latest()->first();
      $currentStatus = $latestActivity?->status;

      if ($isDeleteFlag) {
        $resultType = 'delete_flag';
      }
      elseif (!$mappedStatus) {
        $resultType = 'unmapped';
      }
      elseif ($currentStatus && $currentStatus->id === $mappedStatus->id) {
        $resultType = 'match';
      }
      else {
        $resultType = 'mismatch';
      }

      $results[] = [
        'identifier'      => $identifier,
        'identifier_type' => $identifierType,
        'device'          => $device,
        'current_status'  => $currentStatus,
        'mapped_status'   => $mappedStatus,
        'delete_flag'     => $isDeleteFlag,
        'result_type'     => $resultType,
      ];
    }

    session(['lc_results' => $results]);

    return redirect()->route('admin.library_comparison.index');
  }

  /**
   * Update the status of an existing device (add a new activity record).
   */
  public function updateStatus(Request $request) {
    $request->validate([
      'device_id' => 'required|exists:devices,id',
      'status_id' => 'required|exists:statuses,id',
    ]);

    Activity::create([
      'device_id' => $request->device_id,
      'status_id' => $request->status_id,
      'username' => Auth::user()->first_name . ' ' . Auth::user()->last_name,
      'notes' => 'Status updated via library comparison tool.',
    ]);

    return redirect()->route('admin.library_comparison.recompare')
      ->with('success', 'Status updated successfully.');
  }

  /**
   * Add a new device and its initial activity.
   */
  public function addDevice(Request $request) {
    $request->validate([
      'srjc_tag'        => 'required|string|unique:devices,srjc_tag',
      'serial_number'     => 'required|string|unique:devices,serial_number',
      'model_name' => 'required|string|max:255',
      'pool_id'    => 'required|exists:pools,id',
      'status_id'  => 'required|exists:statuses,id',
    ]);

    DB::transaction(function () use ($request) {
        $device = Device::create([
          'srjc_tag'        => $request->srjc_tag,
          'serial_number'     => $request->serial_number,
          'model_name' => $request->model_name,
          'pool_id'    => $request->pool_id,
        ]);

        Activity::create([
          'device_id' => $device->id,
          'status_id' => $request->status_id,
        ]);
    });

    return back()->with('success', 'Device added successfully.');
  }

  /**
   * Flag a device for manual review (lost and paid case).
   */
  public function flagDevice(Request $request) {
    $request->validate([
      'device_id' => 'required|exists:devices,id',
      'note'      => 'nullable|string|max:500',
    ]);

    // You can expand this — e.g. store in a flags/notes table.
    // For now we store a special activity note or separate mechanism.
    // Here we just add an activity with a "flagged" status if one exists,
    // otherwise we rely on your existing flagging mechanism.
    $device = Device::findOrFail($request->device_id);
    $device->update([
      'flagged_for_review' => TRUE,
      'flag_note'          => $request->note,
    ]);

    return back()->with('success', "Device {$device->srjc_tag} flagged for manual review.");
  }

  /**
   * Helpers.
   */

  /**
   * Resolve the internal mapping for an incoming status label.
   *
   * Label is combined key expose in the dropdown (see incomingStatusOptions).
   */
  private function resolveMapping(string $incomingLabel): array {
    $map = config('library_status_map');

    if (strtolower($incomingLabel) === strtolower($map['item_in_place']['label'])) {
      return ['status' => $map['item_in_place']['status']];
    }

    // Lowercase all keys for case-insensitive matching.
    $reasons = collect($map['reasons'])
      ->mapWithKeys(fn($value, $key) => [strtolower($key) => $value])
      ->all();

    $key = strtolower($incomingLabel);

    return $reasons[$key] ?? ['status' => NULL, 'delete_flag' => FALSE];
  }

  /**
   * Build the list of incoming status options for the dropdown.
   *
   * These are the labels as they appear in the library's export.
   */
  private function incomingStatusOptions(): array {
    $map = config('library_status_map');

    $options = [
      $map['item_in_place']['label'] => 'Item in place → ' . $map['item_in_place']['status'],
    ];

    foreach ($map['reasons'] as $key => $mapping) {
      $label = ucfirst($key);
      $target = $mapping['delete_flag'] ?? FALSE
          ? '(Flag for review)'
          : '→ ' . ($mapping['status'] ?? 'Unmapped');

      $options[$key] = "{$label} {$target}";
    }

    return $options;

  }

  /**
   * Bulk update statuses for multiple devices at once.
   */
  public function updateAll(Request $request) {
    $request->validate([
      'updates'   => 'required|array',
      'updates.*' => 'string',
    ]);

    foreach ($request->updates as $update) {
      [$deviceId, $statusId] = explode(':', $update);
      Activity::create([
        'device_id' => $deviceId,
        'status_id' => $statusId,
        'username'  => Auth::user()->first_name . ' ' . Auth::user()->last_name,
        'notes' => 'Status updated via library comparison tool.',
      ]);
    }

    return redirect()->route('admin.library_comparison.recompare')
      ->with('success', count($request->updates) . ' devices updated successfully.');
  }

  /**
   * Bulk flag multiple devices for review at once.
   */
  public function flagAll(Request $request) {
    $request->validate([
      'device_ids'   => 'required|array',
      'device_ids.*' => 'exists:devices,id',
      'note'         => 'nullable|string',
    ]);

    Device::whereIn('id', $request->device_ids)
      ->update([
        'flagged_for_review' => TRUE,
        'flag_note'          => $request->note,
      ]);

    return redirect()->route('admin.library_comparison.recompare')
      ->with('success', count($request->device_ids) . ' devices flagged for review.');
  }

}
