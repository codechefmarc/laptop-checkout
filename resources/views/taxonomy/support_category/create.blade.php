<x-layout>
  <x-slot:heading>
    Create Support Category
  </x-slot:heading>

  <form method="POST" action="{{ route('taxonomy.support_category.store') }}">
    @csrf
    @include('taxonomy.support_category._form', ['buttonText' => 'Add Support Category'])
  </form>
</x-layout>
