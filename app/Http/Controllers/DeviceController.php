<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Pool;
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
    $pools = Pool::all();
    return view('device.edit', [
      'device' => $device,
      'pools' => $pools,
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
        'nullable',
        'sometimes',
        Rule::unique('devices', 'srjc_tag')->ignore($device->id),
      ],
      'serial_number' => [
        Rule::unique('devices', 'serial_number')->ignore($device->id),
      ],
      'model_number' => ['required'],
      'pool_id' => ['required'],
    ];

    $validated = request()->validate($validationRules);

    $device->update([
      'srjc_tag' => $validated['srjc_tag'],
      'serial_number' => $validated['serial_number'],
      'model_number' => $validated['model_number'],
      'pool_id' => $validated['pool_id'],
    ]);

    $returnUrl = $request->get('return_url', route('log'));
    return redirect($returnUrl)->with('success', 'Device successfully updated.');
  }

  /**
   * Delete device. This also deletes associated activities.
   */
  public function delete(Device $device) {
    $device->delete();
    return redirect()->route('log')->with('success', 'Device and associated activities deleted.');
  }

}
