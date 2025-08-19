<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Protects user management routes.
 */
class CanEditMiddleware {

  /**
   * Protects user management routes.
   */
  public function handle(Request $request, \Closure $next) {
    if (!Auth::user()->canEdit()) {
      return redirect('/search')->with('error', 'Access denied. You do not have permission to access this page.');
    }

    return $next($request);
  }

}
