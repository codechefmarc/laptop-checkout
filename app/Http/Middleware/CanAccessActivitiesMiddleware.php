<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Is student middleware.
 */
class CanAccessActivitiesMiddleware {

  /**
   * Allow access if user can edit OR is a student.
   */
  public function handle(Request $request, \Closure $next) {
    $user = Auth::user();

    if (!$user->canEdit() && !$user->isStudent()) {
      return redirect('/search')->with('error', 'Access denied. You do not have permission to access this page.');
    }

    return $next($request);
  }

}
