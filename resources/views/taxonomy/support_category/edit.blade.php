<x-layout>
  <x-slot:heading>
    Edit Support Category
  </x-slot:heading>

  <form method="POST" action="{{ route('taxonomy.support_category.update', $supportCategory) }}">
    @csrf
    @method('PUT')
    @include('taxonomy.support_category._form' , ['buttonText' => 'Update Support Category'])
  </form>
</x-layout>
