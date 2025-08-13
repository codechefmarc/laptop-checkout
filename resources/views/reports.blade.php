<x-layout>
  <x-slot:heading>
    Reports
  </x-slot:heading>


<div class="flex justify-between max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md">
  <div>
    <h3 class="font-bold text-xl text-gray-600 mb-3">Select a report</h3>
      <ul>
        <li><a href="?report=all_devices">All Devices</a></li>
        <li><a href="?report=inactive">Inactive Devices</a></li>
      </ul>
  </div>

  <div class="report-quick-facts">
    <h3 class="font-bold text-xl text-gray-600 mb-3">Quick Facts</h3>
    <ul>
      <li>Total Devices:</li>
    </ul>
    <h3>Device Current Status</h3>
    <small>Click on a status to view current devices.</small>
    <ul>


    </ul>
  </div>
</div>


  @if($activities !== null)
    <x-activity-list :activities="$activities">
      Report Results
    </x-activity-list>
  @endif

</x-layout>
