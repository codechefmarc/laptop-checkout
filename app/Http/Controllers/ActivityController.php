<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Device;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controller for Activity related routing.
 */
class ActivityController extends Controller {

  /**
   * The main activity log page.
   */
  public function logActivity() {
    return view('log-activity');
  }

  /**
   * Create a new activity.
   */
  public function store() {
    // For use with the device details modal.
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
      $basicRules['pool_id'] = ['required', 'exists:pools,id'];

      $basicRules['serial_number'][] = 'unique:devices,serial_number';
      if (request()->filled('srjc_tag')) {
        $basicRules['srjc_tag'][] = 'unique:devices,srjc_tag';
      }

    }

    $validated = request()->validate($basicRules);

    $identifier = $validated['srjc_tag'] ?? $validated['serial_number'];

    $device = Device::findBySrjcOrSerial($identifier);

    if (!$device) {
      if (Auth::user()->hasRole('student')) {
        return redirect()->back()
          ->withInput()
          ->with('error', 'Device not found. Students cannot create new devices. Please contact ITC staff for assistance.');
      }
      if ($isCreatingDevice) {
        $device = Device::create([
          'srjc_tag' => $validated['srjc_tag'] ?? NULL,
          'serial_number' => $validated['serial_number'],
          'model_number' => $validated['model_number'],
          'pool_id' => $validated['pool_id'],
        ]);

        session()->forget(['device_not_found', 'device_data']);

      }
      else {
        // Device doesn't exist and we don't have creation data yet.
        return redirect()->back()
          ->withInput()
          ->with('device_not_found', TRUE)
          ->with('device_data', [
            'srjc_tag' => $validated['srjc_tag'] ?? NULL,
            'serial_number' => $validated['serial_number'],
          ]);
      }
    }
    else {
      session()->forget(['device_not_found', 'device_data']);
    }

    // Check for duplicate activity (same status as current).
    $deviceCurrentActivity = $device->activities()->latest()->first();
    if (
      $deviceCurrentActivity
      && $deviceCurrentActivity->status_id == $validated['status_id']
      && !request('force_duplicate')
      ) {
      return redirect()->back()
        ->withInput()
        ->with('duplicate_activity', [
          'status_id' => $validated['status_id'],
          'activity_date' => $deviceCurrentActivity->created_at->format('m/d/Y'),
          'status_name' => Status::find($validated['status_id'])->status_name,
          'tailwind_class' => Status::find($validated['status_id'])->tailwind_class,
          'srjc_tag' => $validated['srjc_tag'] ?? NULL,
          'serial_number' => $validated['serial_number'],
          'notes' => $validated['notes'] ?? NULL,
        ]);
    }

    Activity::create([
      'device_id' => $device->id,
      'status_id' => $validated['status_id'],
      'notes' => $validated['notes'],
      'username' => Auth::user()->first_name . ' ' . Auth::user()->last_name,
    ]);

    // Saves the status for ease of adding multiple devices one after another.
    session(['saved_status' => $validated['status_id']]);
    $returnUrl = request()->input('return_url', route('log'));
    return redirect($returnUrl)->with('success', 'Activity successfully added.');
  }

  /**
   * Edit activity.
   */
  public function edit(Activity $activity) {
    $statuses = Status::all();
    $returnUrl = url()->previous();
    return view('activity.edit', [
      'activity' => $activity,
      'statuses' => $statuses,
      'returnUrl' => $returnUrl,
    ]);
  }

  /**
   * Update activity.
   */
  public function patch(Activity $activity, Request $request) {
    // @todo Add LDAP authorize (on hold).
    $validationRules = [
      'status_id' => ['required'],
      'notes' => ['nullable'],
    ];

    $validated = request()->validate($validationRules);

    $activity->update([
      'status_id' => $validated['status_id'],
      'notes' => $validated['notes'],
    ]);

    $returnUrl = $request->get('return_url', route('log'));
    return redirect($returnUrl)->with('success', 'Activity successfully updated.');
  }

  /**
   * Delete activity.
   */
  public function delete(Activity $activity, Request $request) {
    $activity->delete();
    $returnUrl = $request->get('return_url', route('log'));
    return redirect($returnUrl)->with('success', 'Activity deleted.');
  }

}
