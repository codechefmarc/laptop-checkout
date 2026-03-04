<x-layout>
  <x-slot:heading>
    Walk In Log
  </x-slot:heading>

  <x-walk-in.form :supportCategories="$supportCategories" />

  <x-walk-in.list :type="'Active'" :walkIns="$activeWalkIns" />

  <x-walk-in.list :type="'Completed'" :walkIns="$completedWalkIns" />

</x-layout>
