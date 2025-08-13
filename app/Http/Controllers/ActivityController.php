<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Device;
use Illuminate\Http\Request;
use App\Models\Status;

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
      'username' => ['nullable'],
      'notes' => ['nullable'],
    ];

    if ($isCreatingDevice) {
      $basicRules['model_number'] = ['required'];
      $basicRules['serial_number'] = ['required'];

      $basicRules['serial_number'][] = 'unique:devices,serial_number';
      if (request()->filled('srjc_tag')) {
        $basicRules['srjc_tag'][] = 'unique:devices,srjc_tag';
      }

    }

    $validated = request()->validate($basicRules);

    session(['saved_status' => $validated['status_id']]);

    //$device = $this->findExactDevice($validated);

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
          ]);
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
      'notes' => $validated['notes'],
      'username' => $validated['username'],
    ]);

    return redirect()->back()->with('success', 'Activity successfully added.');
  }

  public function edit(Activity $activity) {
    $statuses = Status::all();
    $returnUrl = url()->previous();
    return view('activity.edit', [
      'activity' => $activity,
      'statuses' => $statuses,
      'returnUrl' => $returnUrl,
    ]);
  }

  public function patch(Activity $activity, Request $request) {
    // authorize (on hold)
    // validate

    $validationRules = [
      'status_id' => ['required'],
      'notes' => ['nullable'],
    ];

    $validated = request()->validate($validationRules);
    // Update the activity
    $activity->update([
      'status_id' => $validated['status_id'],
      'notes' => $validated['notes'],
    ]);

    // Redirect to previous listing page
    $returnUrl = $request->get('return_url', '/');
    return redirect($returnUrl)->with('success', 'Activity successfully updated.');
  }

  public function delete(Activity $activity, Request $request) {
    $activity->delete();
    $returnUrl = $request->get('return_url', '/');
    return redirect($returnUrl)->with('success', 'Activity deleted.');
  }

}
