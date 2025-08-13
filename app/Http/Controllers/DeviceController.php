<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDeviceRequest;
use App\Http\Requests\UpdateDeviceRequest;
use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DeviceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDeviceRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Device $device)
    {
        //
    }

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
    // authorize (on hold)
    // validate

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

    // Update the device
    $device->update([
      'srjc_tag' => $validated['srjc_tag'],
      'serial_number' => $validated['serial_number'],
      'model_number' => $validated['model_number'],
    ]);

    // Redirect to job specific page
    $returnUrl = $request->get('return_url', '/');
    return redirect($returnUrl)->with('success', 'Device successfully updated.');
  }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Device $device)
    {
        //
    }

  public function delete(Device $device) {
    $device->delete();
    return redirect('/')->with('success', 'Device and associated activities deleted.');
  }

}
