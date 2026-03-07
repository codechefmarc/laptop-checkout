<x-layout>
<x-slot:heading>
    {{ isset($role) ? 'Edit Role' : 'Create New Role' }}
</x-slot:heading>

<form method="POST" action="{{ isset($role) ? route('admin.roles.update', $role) : route('admin.roles.store') }}">
    @csrf
    @if(isset($role))
        @method('PUT')
    @endif

    <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md">
        <div class="flex flex-col gap-y-4">
            <h3 class="text-2xl">Role Information</h3>

            {{-- Display Name --}}
            <div>
                <label for="display_name" class="block text-sm font-medium text-gray-700 mb-2">
                    Display Name <span class="text-red-500 text-sm">*</span>
                </label>
                <div class="flex items-center rounded-md bg-white pl-3 outline-1 -outline-offset-1 outline-gray-300 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-600">
                    <input
                        id="display_name"
                        type="text"
                        name="display_name"
                        required
                        placeholder="e.g. IT Staff"
                        value="{{ old('display_name', $role->display_name ?? '') }}"
                        class="block min-w-0 grow py-1.5 pr-3 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none sm:text-sm/6" />
                </div>
                @error('display_name')
                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Machine Name - only on create --}}
            @if(!isset($role))
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Machine Name <span class="text-red-500 text-sm">*</span>
                    <span class="text-gray-400 font-normal">(lowercase, underscores only, cannot be changed later)</span>
                </label>
                <div class="flex items-center rounded-md bg-white pl-3 outline-1 -outline-offset-1 outline-gray-300 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-600">
                    <input
                        id="name"
                        type="text"
                        name="name"
                        required
                        placeholder="e.g. it_staff"
                        value="{{ old('name') }}"
                        class="block min-w-0 grow py-1.5 pr-3 text-base font-mono text-gray-900 placeholder:text-gray-400 focus:outline-none sm:text-sm/6" />
                </div>
                @error('name')
                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                @enderror
            </div>
            @else
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Machine Name</label>
                <p class="font-mono text-sm text-gray-500 bg-gray-50 border border-gray-200 rounded-md px-3 py-2">{{ $role->name }}</p>
            </div>
            @endif

            {{-- Permissions --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Permissions
                    <span class="text-gray-400 font-normal">(select all that apply)</span>
                </label>
                <div class="border border-gray-300 rounded-md px-4 py-3 grid grid-cols-1 sm:grid-cols-2 gap-y-2 gap-x-6">
                    @foreach($permissions as $permission)
                        <div class="flex items-center gap-2">
                            <input
                                type="checkbox"
                                id="permission_{{ $loop->index }}"
                                name="permissions[]"
                                value="{{ $permission }}"
                                class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                                {{ in_array($permission, old('permissions', isset($role) ? $role->permissions->pluck('name')->toArray() : [])) ? 'checked' : '' }}
                            >
                            <label for="permission_{{ $loop->index }}" class="text-sm text-gray-700 font-mono cursor-pointer">
                                {{ $permission }}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

        <div class="mt-8 flex items-center justify-end gap-x-6">
            <a href="{{ route('admin.roles.index') }}" class="text-sm/6 font-semibold text-gray-900">Cancel</a>
            <button type="submit"
                class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                {{ isset($role) ? 'Update Role' : 'Create Role' }}
            </button>
        </div>
    </div>
</form>

<script>
    // Auto-generate machine name from display name on create
    const displayName = document.getElementById('display_name');
    const machineName = document.getElementById('name');

    if (displayName && machineName) {
        displayName.addEventListener('input', () => {
            machineName.value = displayName.value
                .toLowerCase()
                .replace(/[^a-z0-9]+/g, '_')
                .replace(/^_|_$/g, '');
        });
    }
</script>
</x-layout>
