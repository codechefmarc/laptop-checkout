<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Http\Request;
/**
 * Handles status requests.
 */
class StatusController extends Controller {

  /**
   * Shows all statuses.
   */
  public function index() {
    $statuses = Status::orderBy('weight', 'asc')->get();
    return view('taxonomy.status.index', compact('statuses'));
  }

  public function create() {
    $returnUrl = url()->previous();
    return view('taxonomy.status.create', [
      'returnUrl' => $returnUrl,
    ]);
  }

  public function store(Request $request) {
    $validated = $request->validate([
      'status_name' => 'required|string|max:255',
      'tailwind_class' => 'required|string',
      'weight' => 'required|integer',
    ]);

    Status::create($validated);

    return redirect()->to(route('taxonomy.status.index'))->with('success', 'Status created successfully.');
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Status $status) {
    $returnUrl = url()->previous();
    return view('taxonomy.status.edit', [
      'status' => $status,
      'returnUrl' => $returnUrl,
    ]);
  }

  public function update(Request $request, Status $status) {

  $validated = $request->validate([
      'status_name' => 'required|string|max:255',
      'tailwind_class' => 'required|string',
      'weight' => 'required|integer',
    ]);

    $status->update($validated);

    return redirect()->to(route('taxonomy.status.index'))->with('success', 'Status updated successfully.');
}

  public function reorder(Request $request) {
    $validated = $request->validate([
        'order' => 'required|array',
        'order.*' => 'integer|exists:statuses,id',
    ]);

    foreach ($validated['order'] as $index => $id) {
        Status::where('id', $id)->update(['weight' => ($index + 1) * 10]);
    }

    return response()->json(['success' => true]);
}

}
