<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller {

    /**
     * List all roles.
     */
    public function index() {
        $permissions = config('permissions');
        $roles = Role::withCount(['permissions', 'users'])->orderBy('name')->get();
        return view('admin.roles.index', compact('roles', 'permissions'));
    }

    /**
     * Create role form.
     */
    public function create() {
        $permissions = config('permissions');
        return view('admin.roles.form', compact('permissions'));
    }

    /**
     * Store new role.
     */
    public function store(Request $request) {
        $request->validate([
            'name'         => 'required|string|max:255|unique:roles,name|alpha_dash',
            'display_name' => 'required|string|max:255',
        ]);

        $role = Role::create([
            'name'         => $request->name,
            'display_name' => $request->display_name,
        ]);

        $role->syncPermissions($request->permissions ?? []);

        return redirect()->route('admin.roles.index')->with('success', "Role '{$role->display_name}' created successfully!");
    }

    /**
     * Edit role form.
     */
    public function edit(Role $role) {
        $permissions = config('permissions');
        return view('admin.roles.form', compact('role', 'permissions'));
    }

    /**
     * Update role.
     */
    public function update(Request $request, Role $role) {
        $request->validate([
            'display_name' => 'required|string|max:255',
        ]);

        $role->update(['display_name' => $request->display_name]);
        $role->syncPermissions($request->permissions ?? []);

        return redirect()->route('admin.roles.index')->with('success', "Role '{$role->display_name}' updated successfully!");
    }

    /**
     * Delete role.
     */
    public function destroy(Role $role) {
        if ($role->users()->count() > 0) {
            return redirect()->route('admin.roles.index')->with('error', "Cannot delete '{$role->display_name}' — {$role->users()->count()} user(s) are assigned to it.");
        }

        $role->delete();
        return redirect()->route('admin.roles.index')->with('success', "Role '{$role->display_name}' deleted successfully!");
    }

}
