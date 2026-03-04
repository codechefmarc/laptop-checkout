<x-layout>
  <x-slot:heading>
    Edit Walk In Log Entry
  </x-slot:heading>

  <form method="POST" action="{{ route('walk_in_log.update', $walkIn->id) }}">
    @csrf
    @method('PUT')
    @include('components.walk-in.form', ['buttonText' => 'Update Walk In Log Entry'])
  </form>
</x-layout>
