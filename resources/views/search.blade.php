<x-layout>
  <x-slot:heading>
    Search
  </x-slot:heading>

  <x-search-form/>

  @if($activities !== null)
    <x-activity-list :activities="$activities">
      Activities ({{ $activities->total() }})
    </x-activity-list>
  @endif

  @if($devices !== null)
    <x-device-list :devices="$devices">
      Devices ({{ $devices->total() }})
    </x-device-list>
  @endif


</x-layout>
