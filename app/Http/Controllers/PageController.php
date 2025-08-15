<?php

namespace App\Http\Controllers;

/**
 * Handles generic page controller requests.
 */
class PageController extends Controller {

  /**
   * Handles 404 errors.
   */
  public function notfound() {
    return view('errors.404');
  }

}
