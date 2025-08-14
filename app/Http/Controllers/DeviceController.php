<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Controller for Device related routing.
 */
class DeviceController extends Controller {

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Device $device) {
    $returnUrl = url()->previous();
    return view('device.edit', [
      'device' => $device,
      'returnUrl' => $returnUrl,
    ]);
  }

  /**
   * Update the specified resource in storage.
   */
  public function patch(Device $device, Request $request) {
    // @todo Add LDAP authentication.
    $validationRules = [
      'srjc_tag' => [
        Rule::unique('devices', 'srjc_tag')->ignore($device->id),
      ],
      'serial_number' => [
        Rule::unique('devices', 'serial_number')->ignore($device->id),
      ],
      'model_number' => ['required'],
    ];

    $validated = request()->validate($validationRules);

    $device->update([
      'srjc_tag' => $validated['srjc_tag'],
      'serial_number' => $validated['serial_number'],
      'model_number' => $validated['model_number'],
    ]);

    $returnUrl = $request->get('return_url', '/');
    return redirect($returnUrl)->with('success', 'Device successfully updated.');
  }

  /**
   * Delete device. This also deletes associated activities.
   */
  public function delete(Device $device) {
    $device->delete();
    return redirect('/')->with('success', 'Device and associated activities deleted.');
  }

}
