<?php

namespace App\Http\Controllers;

use App\Models\CaseModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CaseController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = $user->role->role_name ?? '';

        $query = CaseModel::with('officer')->orderBy('case_id');

        if ($role === 'Officer') {
            $query->where('officer_id', $user->user_id);
        } elseif ($role === 'Analyst') {
            $query->whereHas('evidence.testRequests', function($q) use ($user) {
                $q->where('assigned_analyst_id', $user->user_id);
            });
        }

        $cases = $query->get();
        return view('cases.index', compact('cases'));
    }

    public function create()
    {
        $role = Auth::user()->role->role_name ?? '';
        if ($role === 'Analyst') {
            abort(403, 'Analysts are not authorized to create cases.');
        }

        $officers = User::whereHas('role', fn($q) => $q->where('role_name', 'Officer'))->get();
        return view('cases.create', compact('officers'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $role = $user->role->role_name ?? '';
        if ($role === 'Analyst') {
            abort(403, 'Analysts are not authorized to create cases.');
        }

        $validated = $request->validate([
            'case_title'       => 'required|string|max:150',
            'case_type'        => 'nullable|string|max:50',
            'case_description' => 'nullable|string|max:1000',
            'case_status'      => 'required|in:OPEN,CLOSED,PENDING',
            'officer_id'       => $role === 'Officer' ? 'nullable|exists:users,user_id' : 'required|exists:users,user_id',
        ]);

        if ($role === 'Officer') {
            $validated['officer_id'] = $user->user_id;
        }

        CaseModel::create($validated);

        return redirect()->route('cases.index')->with('success', 'Case created successfully.');
    }

    public function edit(CaseModel $case)
    {
        $user = Auth::user();
        $role = $user->role->role_name ?? '';

        if ($role === 'Analyst') {
            abort(403, 'Analysts are not authorized to edit cases.');
        }

        if ($role === 'Officer' && $case->officer_id !== $user->user_id) {
            abort(403, 'You are not assigned to this case.');
        }

        $officers = User::whereHas('role', fn($q) => $q->where('role_name', 'Officer'))->get();
        return view('cases.edit', compact('case', 'officers'));
    }

    public function update(Request $request, CaseModel $case)
    {
        $user = Auth::user();
        $role = $user->role->role_name ?? '';

        if ($role === 'Analyst') {
            abort(403, 'Analysts are not authorized to edit cases.');
        }

        if ($role === 'Officer' && $case->officer_id !== $user->user_id) {
            abort(403, 'You are not assigned to this case.');
        }

        $validated = $request->validate([
            'case_title'       => 'required|string|max:150',
            'case_type'        => 'nullable|string|max:50',
            'case_description' => 'nullable|string|max:1000',
            'case_status'      => 'required|in:OPEN,CLOSED,PENDING',
            'officer_id'       => $role === 'Officer' ? 'nullable|exists:users,user_id' : 'required|exists:users,user_id',
        ]);

        if ($role === 'Officer') {
            $validated['officer_id'] = $user->user_id;
        }

        $case->update($validated);

        return redirect()->route('cases.index')->with('success', 'Case updated successfully.');
    }

    public function destroy(CaseModel $case)
    {
        $user = Auth::user();
        $role = $user->role->role_name ?? '';

        if ($role === 'Analyst') {
            abort(403, 'Analysts are not authorized to delete cases.');
        }

        if ($role === 'Officer' && $case->officer_id !== $user->user_id) {
            abort(403, 'You are not assigned to this case.');
        }

        $case->delete();
        return redirect()->route('cases.index')->with('success', 'Case deleted successfully.');
    }
}
