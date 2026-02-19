<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Device;
use Illuminate\Http\Request;

/**
 * Controller for managing devices that have been flagged for review.
 */
class FlaggedDeviceController extends Controller {

  /**
   * Display a listing of flagged devices.
   */
  public function index() {
    $devices = Device::where('flagged_for_review', TRUE)
      ->with(['activities.status'])
      ->orderBy('updated_at', 'desc')
      ->get();

    return view('admin.flagged-devices.index', compact('devices'));
  }

  /**
   * Remove the specified device from storage.
   */
  public function destroy(Device $device) {
    $device->delete();

    return redirect()->route('admin.flagged_devices.index')
      ->with('success', "Device {$device->srjc_tag} and all associated activities deleted.");
  }

  /**
   * Bulk delete flagged devices.
   */
  public function bulkDestroy(Request $request) {
    $request->validate([
      'device_ids'   => 'required|array',
      'device_ids.*' => 'exists:devices,id',
    ]);

    $devices = Device::whereIn('id', $request->device_ids)->get();
    $count = $devices->count();
    $devices->each->delete();

    return redirect()->route('admin.flagged_devices.index')
      ->with('success', $count . ' devices permanently deleted.');
  }

}
