<x-layout>
  <x-slot:heading>
    Walk In Log
  </x-slot:heading>

  @php
    if ($activeWalkInsCurrentUser > 2) {
      session()->flash('info', "You have {$activeWalkInsCurrentUser} active walk-in entries. Make sure to complete them when finished.");
    }
  @endphp

  <x-walk-in.form :supportCategories="$supportCategories" />

  <x-walk-in.list :type="'Active'" :walkIns="$activeWalkIns" />

  <x-walk-in.list :type="'Completed'" :walkIns="$completedWalkIns" />

</x-layout>
