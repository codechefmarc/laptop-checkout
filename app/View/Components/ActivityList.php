<?php

namespace App\View\Components;

use App\Models\Activity;
use Illuminate\View\Component;

class ActivityList extends Component {
  public $activities;
  public $title;

  public function __construct($activities = null, $title = "Activities") {
    // If no activities passed, default to today's
    $this->activities = $activities ?? Activity::with(['device', 'status'])
      ->whereDate('created_at', today())
      ->latest('created_at')
      ->paginate(20);

    $this->title = $title;

  }

  public function render() {
    return view('components.activity-list');
  }

}
