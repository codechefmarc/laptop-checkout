<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;

class ModelNumberController extends Controller {
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
