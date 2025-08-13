<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDeviceRequest;
use App\Http\Requests\UpdateDeviceRequest;
use App\Models\Device;

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
      return view('device.edit', [
        'device' => $device,
      ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function patch(Device $device, Request $request) {
    // authorize (on hold)
    // validate

      dd("ho!!");

    $validationRules = [
      'srjc_tag' => ['required_without:serial_number'],
      'serial_number' => ['required'],
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
    return redirect('/')->with('success', 'Device and activity successfully updated.');
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
