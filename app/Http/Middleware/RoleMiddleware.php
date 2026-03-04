<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Role middleware for checking if a user has one of multiple roles.
 */
class RoleMiddleware {

  /**
   * Check if the authenticated user's role is in the allowed roles.
   */
  public function handle(Request $request, \Closure $next, string ...$roles) {
    if (!in_array(Auth::user()->role->name, $roles)) {
      return redirect('/search')->with('error', 'Access denied.');
    }
    return $next($request);
  }

}
