<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Device;
use Illuminate\Http\Request;

class ActivityController extends Controller {

  public function logActivity() {
    return view('log-activity');
  }

  public function store() {
    // First, check if this is a second submission with device creation data
    $isCreatingDevice = request()->has('creating_device');

    $basicRules = [
      'srjc_tag' => ['required_without:serial_number'],
      'serial_number' => ['required_without:srjc_tag'],
      'status_id' => ['required'],
    ];

    if ($isCreatingDevice) {
      $basicRules['model_number'] = ['required'];
    }

    $validated = request()->validate($basicRules);

    session(['saved_status' => $validated['status_id']]);

    $device = Device::findBySrjcOrSerial($validated['srjc_tag'] ?? null, $validated['serial_number'] ?? null);

    if (!$device) {
      // If we have device creation data, create the device
      if ($isCreatingDevice) {
        $device = Device::create([
          'srjc_tag' => $validated['srjc_tag'] ?? null,
          'serial_number' => $validated['serial_number'] ?? null,
          'model_number' => $validated['model_number'],
          // Add other device fields
        ]);

        // Clear the session flags
        session()->forget(['device_not_found', 'device_data']);

      // Continue to create activity below...
      } else {
        // Device doesn't exist and we don't have creation data yet
        // Return with flag to show hidden fields
        return redirect()->back()
          ->withInput()
          ->with('device_not_found', true)
          ->with('device_data', [
            'srjc_tag' => $validated['srjc_tag'] ?? null,
            'serial_number' => $validated['serial_number'] ?? null,
          ])
          ->with('info', 'Device not found. Please provide additional details to create it.');
      }
    }
    else {
      // Device found, clear any session flags
      session()->forget(['device_not_found', 'device_data']);
    }

    // Create the activity (this runs whether device was found or just created)
    Activity::create([
      'device_id' => $device->id,
      'status_id' => $validated['status_id'],
      'notes' => request()->get('notes'),
      'username' => request()->get('username'),
    ]);

    return redirect()->back()->with('success', 'Activity successfully added.');
  }

}
