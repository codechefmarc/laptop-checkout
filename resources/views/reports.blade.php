<x-layout>
  <x-slot:heading>
    Reports
  </x-slot:heading>

<div class="md:flex justify-between max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md">
  <div>
    <h3 class="font-bold text-xl text-gray-600 mb-3">Devices</h3>
    <ul>
      <li><a class="text-blue-500" href="?report=active_devices">Active Devices</a> ({{ $active_device_count }})</li>
      <li><a class="text-blue-500" href="?report=surplus_devices">Surplus Devices</a> ({{ $surplus_device_count }})</li>
      <li><a class="text-blue-500" href="?report=inactive_devices">Devices Without Activities</a> ({{ $inactive_device_count }})</li>
      <li><a class="text-blue-500" href="?report=all_devices">Total Devices</a> ({{ $total_device_count }})</li>
    </ul>
    <h3 class="font-bold text-xl text-gray-600 mb-3 mt-3">Active Devices by Model</h3>
      <ul>
        @foreach($active_device_model_counts as $device)
          @php
            if ($device->model_number == "Unknown Model") {
              $link = route('search', [ 'show_empty_models' => 'true', 'current_status_only' => 'on', 'status_id' => 'not_surplus']);
            } else {
              $link = route('search', [ 'model_number' => $device->model_number, 'current_status_only' => 'on', 'status_id' => 'not_surplus']);
            }
          @endphp
          <li>
            <a class="text-blue-500" href="{{ $link }}"> {{ $device->model_number }}</a> ({{ $device->device_count }})
          </li>
        @endforeach
      </ul>
    <h3 class="font-bold text-xl text-gray-600 mb-3 mt-3">Surplus Devices by Model</h3>
      <ul>
        @foreach($surplus_device_model_counts as $device)
          @php
            if ($device->model_number == "Unknown Model") {
              $link = route('search', [ 'show_empty_models' => 'true', 'current_status_only' => 'on', 'status_id' => $surplus_status_id]);
            } else {
              $link = route('search', [ 'model_number' => $device->model_number, 'current_status_only' => 'on', 'status_id' => $surplus_status_id]);
            }
          @endphp
          <li>
            <a class="text-blue-500" href="{{ $link }}"> {{ $device->model_number }}</a> ({{ $device->device_count }})
          </li>
        @endforeach
      </ul>
  </div>

  <div class="report-quick-facts">
    <h3 class="font-bold text-xl text-gray-600 mb-3">Current Activity by Status</h3>
    <ul>
    @foreach($status_counts as $status)
      <li><a class="text-blue-500" href="{{ route('search', ['status_id' => $status->status_id, 'current_status_only' => 'on']) }}"> {{ $status->status_name }}</a> ({{ $status->device_count }})</li>
    @endforeach

    </ul>

    <h3 class="font-bold text-xl text-gray-600 mb-3 mt-3">Current Activity by Pool</h3>
    <ul>
      @foreach($pool_counts_current as $pool)
        <li>
          <a class="text-blue-500" href="{{ route('search', ['pool_id' => $pool->pool_id, 'current_status_only' => 'on']) }}"> {{ $pool->pool_name }}</a> ({{ $pool->device_count }})
        </li>
      @endforeach
    </ul>
  </div>
</div>


  @if($activities !== null)
    <x-activity-list :activities="$activities">
      Report Results
    </x-activity-list>
  @endif

  @if($devices !== null)
    <x-device-list :devices="$devices">
      {{ $report_title }}
    </x-device-list>
  @endif

</x-layout>
