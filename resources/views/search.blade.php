<x-layout>
  <x-slot:heading>
    Search
  </x-slot:heading>

  <x-search-form/>

  @if($activities !== null)
    <x-activity-list :activities="$activities">
      Activities ({{ $activities->total() }} found)
    </x-activity-list>
  @endif

  @if($devices !== null)
    <x-device-list :devices="$devices">
      Devices ({{ $devices->total() }} found)
    </x-device-list>
  @endif


</x-layout>
