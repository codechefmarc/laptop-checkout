<x-layout>
<x-slot:heading>Manage Roles</x-slot:heading>

<div class="max-w-6xl mx-auto bg-white p-8 rounded-lg shadow-md">

    <div class="flex justify-between items-center mb-6">
        <h3 class="text-2xl font-semibold text-gray-900">Roles</h3>
        <a href="{{ route('admin.roles.create') }}"
            class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500">
            Create New Role
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-md bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 rounded-md bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif

    <div class="overflow-x-auto border border-gray-200 rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Display Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Machine Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Permissions</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Users</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($roles as $role)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ $role->display_name ?? $role->name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="font-mono text-xs text-gray-500">{{ $role->name }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-indigo-100 text-indigo-800">
                            {{ $role->permissions_count }} permissions
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $role->users_count }} {{ Str::plural('user', $role->users_count) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end space-x-2">
                            <a href="{{ route('admin.roles.edit', $role) }}"
                                class="text-indigo-600 hover:text-indigo-900 font-medium">Edit</a>

                            @if($role->users_count === 0)
                                <button
                                    form="delete-role-form-{{ $role->id }}"
                                    class="text-red-600 hover:text-red-900 font-medium"
                                    onclick="return confirm('Delete role {{ $role->display_name ?? $role->name }}?')">
                                    Delete
                                </button>
                            @else
                                <span class="text-gray-300 font-medium" title="Cannot delete — users assigned">Delete</span>
                            @endif
                        </div>
                    </td>
                </tr>

                @if($role->users_count === 0)
                <form id="delete-role-form-{{ $role->id }}" method="POST" action="{{ route('admin.roles.destroy', $role) }}" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
                @endif

                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                        No roles found. <a href="{{ route('admin.roles.create') }}" class="text-indigo-600">Create the first role</a>.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4 text-sm text-gray-500">
        Total roles: {{ $roles->count() }}
    </div>

</div>
</x-layout>
