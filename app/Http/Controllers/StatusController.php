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
    return view('status.index', compact('statuses'));
  }

  public function update(Request $request, Status $status) {
    $validated = $request->validate([
      'status_name' => 'required|string|max:255',
      'tailwind_class' => 'required|string',
    ]);

    $status->update($validated);

    return response()->json(['success' => true]);
}

  public function reorder(Request $request) {
    $validated = $request->validate([
      'order' => 'required|array',
      'order.*' => 'integer|exists:statuses,id',
    ]);

    foreach ($validated['order'] as $weight => $id) {
      Status::where('id', $id)->update(['weight' => $weight + 1]);
    }

    return response()->json(['success' => true]);
}

}
