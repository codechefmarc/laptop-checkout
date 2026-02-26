<?php

namespace App\Http\Controllers;

use App\Models\Pool;
use Illuminate\Http\Request;
/**
 * Handles pool requests.
 */
class PoolController extends Controller {

  /**
   * Shows all pools.
   */
  public function index() {
    $pools = Pool::orderBy('weight', 'asc')->get();
    return view('taxonomy.pool.index', compact('pools'));
  }

  public function create() {
    $returnUrl = url()->previous();
    return view('taxonomy.pool.create', [
      'returnUrl' => $returnUrl,
    ]);
  }

  public function store(Request $request) {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'description' => 'nullable|string',
      'weight' => 'required|integer',
    ]);

    Pool::create($validated);

    return redirect()->to(route('taxonomy.pool.index'))->with('success', 'Pool created successfully.');
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Pool $pool) {
    $returnUrl = url()->previous();
    return view('taxonomy.pool.edit', [
      'pool' => $pool,
      'returnUrl' => $returnUrl,
    ]);
  }

  public function update(Request $request, Pool $pool) {

  $validated = $request->validate([
      'name' => 'required|string|max:255',
      'description' => 'nullable|string',
      'weight' => 'required|integer',
    ]);

    $pool->update($validated);

    return redirect()->to(route('taxonomy.pool.index'))->with('success', 'Pool updated successfully.');
}

  public function reorder(Request $request) {
    $validated = $request->validate([
        'order' => 'required|array',
        'order.*' => 'integer|exists:pools,id',
    ]);

    foreach ($validated['order'] as $index => $id) {
        Pool::where('id', $id)->update(['weight' => ($index + 1) * 10]);
    }

    return response()->json(['success' => true]);
}

}
