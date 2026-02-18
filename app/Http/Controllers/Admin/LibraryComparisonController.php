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
    $incomingStatuses = $this->incomingStatusOptions();

    return view('admin.library-comparison.index', compact('statuses', 'pools', 'incomingStatuses'));
  }

  /**
   * Run the comparison and return results.
   */
  public function compare(Request $request) {
    $request->validate([
      'identifiers'       => 'required|string',
      'identifier_type'   => 'required|in:srjc_tag,serial_number',
      'incoming_status'   => 'required|string',
    ]);

    session([
      'lc_identifiers'     => $request->identifiers,
      'lc_identifier_type' => $request->identifier_type,
      'lc_incoming_status' => $request->incoming_status,
    ]);

    // Parse pasted identifiers (one per line, trim whitespace, drop blanks)
    $identifiers = collect(explode("\n", $request->identifiers))
      ->map(fn($line) => trim($line))
      ->filter()
      ->unique()
      ->values();

    // 'tag' or 'serial'
    $identifierType = $request->identifier_type;
    $incomingLabel  = $request->incoming_status;

    // Resolve what our internal status should be for this incoming label.
    $mapping      = $this->resolveMapping($incomingLabel);
    $mappedStatus = isset($mapping['status'])
        ? Status::where('status_name', $mapping['status'])->first()
        : NULL;
    $isDeleteFlag = $mapping['delete_flag'] ?? FALSE;

    $results = [];

    foreach ($identifiers as $identifier) {
      // Find device by tag or serial.
      $device = $identifierType === 'tag'
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
          // Not in our DB.
          'result_type'     => 'not_found',
        ];
        continue;
      }

      // Get most recent activity for this device.
      $latestActivity = Activity::where('device_id', $device->id)
        ->with('status')
        ->latest()
        ->first();

      $currentStatus = $latestActivity?->status;

      // Determine result type.
      if ($isDeleteFlag) {
        $resultType = 'delete_flag';
      }
      elseif (!$mappedStatus) {
        // Incoming status has no mapping.
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

    $statuses         = Status::orderBy('weight')->get();
    $pools            = Pool::orderBy('name')->get();
    $models           = Device::select('model_number')->distinct()->orderBy('model_number')->pluck('model_number');
    $incomingStatuses = $this->incomingStatusOptions();

    return view('admin.library-comparison.index', compact(
        'results', 'statuses', 'pools', 'incomingStatuses', 'models',
    ))->with([
      'last_identifiers'     => $request->identifiers,
      'last_identifier_type' => $identifierType,
      'last_incoming_status' => $incomingLabel,
    ]);
  }

  /**
   * Re-run comparison with last input (after taking actions).
   */
  public function recompare() {
    if (!session()->has('lc_identifiers')) {
      return redirect()->route('admin.library_comparison.index');
    }

    $request = new Request();
    $request->replace([
      'identifiers'     => session('lc_identifiers'),
      'identifier_type' => session('lc_identifier_type'),
      'incoming_status' => session('lc_incoming_status'),
    ]);

    return $this->compare($request);
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
    ]);

    return redirect()->route('library-comparison.recompare')
      ->with('success', 'Status updated successfully.');
  }

  /**
   * Add a new device and its initial activity.
   */
  public function addDevice(Request $request) {
    $request->validate([
      'tag'        => 'required|string|unique:devices,tag',
      'serial'     => 'required|string|unique:devices,serial',
      'model_name' => 'required|string|max:255',
      'pool_id'    => 'required|exists:pools,id',
      'status_id'  => 'required|exists:statuses,id',
    ]);

    DB::transaction(function () use ($request) {
        $device = Device::create([
          'tag'        => $request->tag,
          'serial'     => $request->serial,
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
    $device->update(['flagged_for_review' => TRUE, 'flag_note' => $request->note]);

    return back()->with('success', "Device {$device->tag} flagged for manual review.");
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

    // Check "item in place" case.
    if (strtolower($incomingLabel) === strtolower($map['item_in_place']['label'])) {
      return ['status' => $map['item_in_place']['status']];
    }

    // Check reasons.
    $reasons = $map['reasons'];
    $key     = strtolower($incomingLabel);

    return $reasons[$key] ?? ['status' => NULL, 'delete_flag' => NULL];
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
          ? '⚑ Flag for review (lost & paid)'
          : '→ ' . ($mapping['status'] ?? 'Unmapped');

      $options[$key] = "{$label} {$target}";
    }

    return $options;

  }

}
