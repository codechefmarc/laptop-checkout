<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;

/**
 * API endpoint to get model numbers for autocomplete.
 */
class ModelNumberController extends Controller {

  /**
   * Search function to return model numbers based on query.
   */
  public function search(Request $request) {
    $query = $request->get('q');

    $modelNumbers = Device::where('model_number', 'LIKE', "%{$query}%")
      ->select('model_number')
      ->distinct()
      ->limit(10)
      ->get();

    return response()->json($modelNumbers);
  }

}
