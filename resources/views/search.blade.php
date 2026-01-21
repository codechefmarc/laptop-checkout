<x-layout>
  <x-slot:heading>
    Search
  </x-slot:heading>

  <x-search-form :statusFilterInfo="$statusFilterInfo" :poolName="$poolName"/>

  @if($activities !== null)
    <x-activity-list :activities="$activities">
      Activities
    </x-activity-list>
  @endif

  @if($devices !== null)
    <x-device-list :devices="$devices">
      Devices ({{ $devices->total() }})
    </x-device-list>
  @endif


</x-layout>
