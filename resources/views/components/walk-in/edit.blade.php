<x-layout>
  <x-slot:heading>
    Edit Walk In Log Entry
  </x-slot:heading>

  <form class="max-w-7xl mx-auto bg-white p-8 rounded-lg shadow-md" method="POST" action="{{ route('walk_in_log.update', $walkIn->id) }}">
    @csrf
    @method('PATCH')
    @include('components.walk-in.form', ['buttonText' => 'Update Walk In Log Entry', 'type' => 'edit'])
  </form>
</x-layout>
