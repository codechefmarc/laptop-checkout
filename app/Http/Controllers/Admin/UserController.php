<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

/**
 * Provides user methods.
 */
class UserController extends Controller {
  use AuthorizesRequests;

  /**
   * Shows all users.
   */
  public function index() {
    $users = User::with('roles')->orderBy('created_at', 'desc')->get();
    return view('admin.users.index', compact('users'));
  }

  /**
   * Create a user form.
   */
  public function create() {
    $roles = Role::orderBy('name')->get();
    return view('admin.users.form', compact('roles'));
  }

  /**
   * Creates a user.
   */
  public function store(Request $request) {
    $request->validate([
      'first_name' => 'required|string|max:255',
      'last_name'  => 'required|string|max:255',
      'email'      => 'required|string|email|max:255|unique:users',
      'password'   => 'required|string|min:8|confirmed',
      'role'       => 'required|exists:roles,name',
    ]);

    $user = User::create([
      'first_name' => $request->first_name,
      'last_name'  => $request->last_name,
      'email'      => $request->email,
      'password'   => Hash::make($request->password),
    ]);

    $user->assignRole($request->role);

    return redirect()->route('admin.users.index')->with('success', 'User created successfully!');
  }

  /**
   * Edit an existing user form.
   */
  public function edit(User $user) {
    $roles = Role::orderBy('name')->get();
    return view('admin.users.form', compact('user', 'roles'));
  }

  /**
   * Update existing user.
   */
  public function update(Request $request, User $user) {

    $this->authorize('edit-user', $user);

    $rules = [
      'first_name' => 'required|string|max:255',
      'last_name'  => 'required|string|max:255',
      'email'      => 'required|string|email|max:255|unique:users,email,' . $user->id,
    ];

    if ($request->filled('password')) {
      $rules['password'] = 'required|string|min:8|confirmed';
    }

    if (Auth::user()->hasRole('admin')) {
      $rules['role'] = 'required|exists:roles,name';
    }

    $request->validate($rules);

    $updateData = [
      'first_name' => $request->first_name,
      'last_name'  => $request->last_name,
      'email'      => $request->email,
    ];

    if ($request->filled('password')) {
      $updateData['password'] = Hash::make($request->password);
    }

    $user->update($updateData);

    if (Auth::user()->hasRole('admin')) {
      $user->syncRoles($request->role);
    }

    if (Auth::user()->hasRole('admin') && Auth::id() !== $user->id) {
      return redirect()->route('admin.users.index')->with('success', 'User updated successfully!');
    }

    return redirect()->route('profile.edit')->with('success', 'Profile updated successfully!');
  }

  /**
   * Delete a user.
   */
  public function destroy(User $user) {
    if ($user->id === Auth::id()) {
      return redirect()->route('admin.users.index')->with('error', 'You cannot delete your own account!');
    }

    if ($user->hasRole('admin')) {
      $adminCount = User::role('admin')->count();

      if ($adminCount <= 1) {
        return redirect()->route('admin.users.index')->with('error', 'Cannot delete the last admin user!');
      }
    }

    $user->delete();

    return redirect()->route('admin.users.index')->with('success', 'User deleted successfully!');
  }

  /**
   * Edit profile form for the authenticated user.
   */
  public function editProfile() {
    $user = Auth::user();
    $roles = Role::orderBy('name')->get();
    $isProfile = TRUE;
    return view('admin.users.form', compact('user', 'roles', 'isProfile'));
}

  /**
   * Update profile for the authenticated user.
   */
  public function updateProfile(Request $request) {
    return $this->update($request, Auth::user());
  }

}
