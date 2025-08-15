<?php

namespace App\View\Components;

use App\Models\Activity;
use Illuminate\View\Component;

/**
 * The list of activities.
 */
class ActivityList extends Component {
  /**
   * A list of activities.
   *
   * @var activities
   */
  public $activities;
  /**
   * The title that shows above the activity list.
   *
   * @var title
   */
  public $title;

  public function __construct($activities = NULL, $title = "Activities") {

    // If no activities passed, default to today's.
    $this->activities = $activities ?? Activity::with(['device', 'status'])
      ->whereDate('created_at', today())
      ->latest('created_at')
      ->paginate(20);

    $this->title = $title;

  }

  /**
   * Render the activity list itself.
   */
  public function render() {
    return view('components.activity-list');
  }

}
