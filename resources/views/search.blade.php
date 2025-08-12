<x-layout>
  <x-slot:heading>
    Search
  </x-slot:heading>

  <x-search-form/>

  @if($activities !== null)
    <x-activity-list :activities="$activities">
      Search Results ({{ $activities->total() }} found)
    </x-activity-list>
  @endif

</x-layout>
