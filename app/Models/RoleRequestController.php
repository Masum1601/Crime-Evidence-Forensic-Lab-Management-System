<?php

namespace App\Http\Controllers;

use App\Models\RoleRequest;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleRequestController extends Controller
{
    // GET /role-request/create — the logged in "User" requests Officer/Analyst
    public function create()
    {
        $roles = Role::whereIn('role_name', ['Officer', 'Analyst'])->get();

        $existingRequest = RoleRequest::where('user_id', Auth::id())
            ->where('status', 'PENDING')
            ->first();

        return view('auth.role_request', compact('roles', 'existingRequest'));
    }

    // POST /role-request
    public function store(Request $request)
    {
        $validated = $request->validate([
            'requested_role_id' => 'required|exists:roles,role_id',
            'reason'            => 'required|string|max:500',
        ]);

        // Prevent duplicate pending requests
        $existing = RoleRequest::where('user_id', Auth::id())->where('status', 'PENDING')->first();
        if ($existing) {
            return back()->with('error', 'You already have a pending role request.');
        }

        RoleRequest::create([
            'user_id'           => Auth::id(),
            'requested_role_id' => $validated['requested_role_id'],
            'reason'            => $validated['reason'],
        ]);

        return redirect()->route('dashboard')->with('success',
            'Your role request has been submitted. An admin will review it shortly.');
    }

    // ---- ADMIN SIDE ----

    // GET /admin/role-requests
    public function index(Request $request)
    {
        $query = RoleRequest::with(['user', 'requestedRole', 'reviewer']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', 'PENDING'); // default view: pending only
        }

        $requests = $query->orderBy('request_id', 'desc')->paginate(10)->withQueryString();
        return view('admin.role_requests.index', compact('requests'));
    }

    // POST /admin/role-requests/{id}/decide
    public function decide(Request $request, RoleRequest $roleRequest)
    {
        $validated = $request->validate([
            'decision'     => 'required|in:APPROVED,REJECTED',
            'review_notes' => 'nullable|string|max:500',
        ]);

        $roleRequest->update([
            'status'       => $validated['decision'],
            'reviewed_by'  => Auth::id(),
            'reviewed_at'  => now(),
            'review_notes' => $validated['review_notes'] ?? null,
        ]);

        // If approved, actually promote the user's role
        if ($validated['decision'] === 'APPROVED') {
            $roleRequest->user->update(['role_id' => $roleRequest->requested_role_id]);
        }

        return back()->with('success', 'Request ' . strtolower($validated['decision']) . '.');
    }
}