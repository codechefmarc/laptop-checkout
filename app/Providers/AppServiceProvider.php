<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

/**
 * Provides application services.
 */
class AppServiceProvider extends ServiceProvider {

  /**
   * Register any application services.
   */
  public function register(): void {

  }

  /**
   * Bootstrap any application services.
   */
  public function boot(): void {
    Gate::define('edit-user', function ($currentUser, $targetUser) {
        return $currentUser->isAdmin() || $currentUser->id === $targetUser->id;
    });
  }

}
