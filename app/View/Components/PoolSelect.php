<?php

namespace App\View\Components;

use App\Models\Pool;
use Illuminate\View\Component;

/**
 * Provides a pool selector with the ability to save state between page loads.
 */
class PoolSelect extends Component {
  /**
   * All pools.
   *
   * @var pools
   */
  public $pools;

  /**
   * Wether the pool is selected or not.
   *
   * @var selected
   */
  public $selected;

  public function __construct($selected = NULL) {
    $this->pools = Pool::orderBy('weight')->get();
    $this->selected = $selected
      ?? old('pool_id')
      ?? session('saved_pool')
      ?? request('pool_id')
      ?? NULL;
  }

  /**
   * Render out the status selector.
   */
  public function render() {
    return view('components.pool-select');
  }

}
