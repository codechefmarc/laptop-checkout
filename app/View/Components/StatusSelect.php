<?php

namespace App\View\Components;

use App\Models\Status;
use Illuminate\View\Component;

/**
 * Provides a status selector with the ability to save state between page loads.
 */
class StatusSelect extends Component {
  /**
   * All statuses.
   *
   * @var statuses
   */
  public $statuses;

  /**
   * Wether the status is selected or not.
   *
   * @var selected
   */
  public $selected;

  public function __construct($selected = NULL) {
    $this->statuses = Status::orderBy('weight')->get();
    //$this->statuses = Status::all();
    $this->selected = $selected
      ?? old('status_id')
      ?? session('saved_status')
      ?? request('status_id')
      ?? NULL;
  }

  /**
   * Render out the status selector.
   */
  public function render() {
    return view('components.status-select');
  }

}
