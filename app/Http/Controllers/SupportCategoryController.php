<?php

namespace App\Http\Controllers;

use App\Models\SupportCategory;
use Illuminate\Http\Request;

/**
 * Handles support category requests.
 */
class SupportCategoryController extends Controller {

  /**
   * Shows all support categories.
   */
  public function index() {
    $supportCategories = SupportCategory::orderBy('weight', 'asc')->get();
    return view('taxonomy.support_category.index', compact('supportCategories'));
  }

  public function create() {
    $returnUrl = url()->previous();
    return view('taxonomy.support_category.create', [
      'returnUrl' => $returnUrl,
    ]);
  }

  public function store(Request $request) {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'description' => 'nullable|string',
      'weight' => 'required|integer',
    ]);

    SupportCategory::create($validated);

    return redirect()->to(route('taxonomy.support_category.index'))->with('success', 'Support category created successfully.');
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(SupportCategory $supportCategory) {
    $returnUrl = url()->previous();
    return view('taxonomy.support_category.edit', [
      'supportCategory' => $supportCategory,
      'returnUrl' => $returnUrl,
    ]);
  }

  public function update(Request $request, SupportCategory $supportCategory) {

    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'description' => 'nullable|string',
      'weight' => 'required|integer',
    ]);

    $supportCategory->update($validated);

    return redirect()->to(route('taxonomy.support_category.index'))->with('success', 'Support category updated successfully.');
  }

  public function reorder(Request $request) {
    $validated = $request->validate([
      'order' => 'required|array',
      'order.*' => 'integer|exists:support_categories,id',
    ]);

    foreach ($validated['order'] as $index => $id) {
      SupportCategory::where('id', $id)->update(['weight' => ($index + 1) * 10]);
    }

    return response()->json(['success' => TRUE]);
  }

}
