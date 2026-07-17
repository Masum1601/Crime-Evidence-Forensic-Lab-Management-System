<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;

class UserController extends Controller
{
   
    public function index()
    {
        $users = User::with('role')->orderBy('user_id')->get();
        $roles = Role::all();
        return view('users.index', compact('users', 'roles'));
    }

   
    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'role_id'   => 'required|exists:roles,role_id',
            'full_name' => 'required|string|max:100',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|string|min:6',
            'phone'     => 'nullable|string|max:20',
            'status'    => 'required|in:ACTIVE,INACTIVE',
        ]);

        $validated['password'] = bcrypt($validated['password']);

        User::create($validated);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

   
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'role_id'   => 'required|exists:roles,role_id',
            'full_name' => 'required|string|max:100',
            'email'     => 'required|email|unique:users,email,' . $user->user_id . ',user_id',
            'phone'     => 'nullable|string|max:20',
            'status'    => 'required|in:ACTIVE,INACTIVE',
        ]);

        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

   
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
    // POST /users/{id}/toggle-status
    public function toggleStatus(User $user)
    {
        $user->status = $user->status === 'ACTIVE' ? 'INACTIVE' : 'ACTIVE';
        $user->save();

        return back()->with('success', 'User status updated.');
    }
    // POST /users/{id}/change-role
    public function changeRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'role_id' => 'required|exists:roles,role_id',
        ]);

        $user->role_id = $validated['role_id'];
        $user->save();

        return back()->with('success', 'User role updated.');
    }
}
