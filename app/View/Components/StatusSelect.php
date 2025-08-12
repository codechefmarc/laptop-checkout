<?php

namespace App\View\Components;

use App\Models\Status;
use Illuminate\View\Component;

class StatusSelect extends Component {
  public $statuses;
  public $selected;

  public function __construct($selected = null) {
    $this->statuses = Status::all();
    $this->selected = $selected
      ?? old('status_id')
      ?? session('saved_status')
      ?? request('status_id')
      ?? null;
  }

  public function render() {
    return view('components.status-select');
  }

}
