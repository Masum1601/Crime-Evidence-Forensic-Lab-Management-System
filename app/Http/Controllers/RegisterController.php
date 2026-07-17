<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    // GET /register
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // POST /register
    public function register(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:100',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|string|min:6|confirmed',
            'phone'     => 'nullable|string|max:20',
        ]);

        // Every self-registered account starts as the plain "User" role.
        // Officer/Analyst access must be explicitly granted by an Admin.
        $userRole = Role::where('role_name', 'User')->first();

        $user = User::create([
            'role_id'   => $userRole->role_id,
            'full_name' => $validated['full_name'],
            'email'     => $validated['email'],
            'password'  => bcrypt($validated['password']),
            'phone'     => $validated['phone'] ?? null,
            'status'    => 'ACTIVE',
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')->with('success',
            'Welcome! Your account has been created. If you need Officer or Analyst access, you can request it from your dashboard.');
    }
}
