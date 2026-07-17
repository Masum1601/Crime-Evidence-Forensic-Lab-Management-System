<?php

namespace App\Http\Controllers;

use App\Models\CustodyRecord;
use App\Models\Evidence;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustodyController extends Controller
{
    // GET /custody — list all custody transfer records
    public function index(Request $request)
    {
        $user = Auth::user();
        $role = $user->role->role_name ?? '';

        $query = CustodyRecord::with(['evidence', 'fromUser', 'toUser', 'transferredByUser']);

        if ($role === 'Officer') {
            $query->whereHas('evidence.case', function($q) use ($user) {
                $q->where('officer_id', $user->user_id);
            });
        } elseif ($role === 'Analyst') {
            $query->whereHas('evidence.testRequests', function($q) use ($user) {
                $q->where('assigned_analyst_id', $user->user_id);
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('evidence', function ($q) use ($search) {
                $q->where('evidence_name', 'like', "%{$search}%");
            });
        }

        $records = $query->orderBy('transfer_date', 'desc')->paginate(10)->withQueryString();

        return view('custody.index', compact('records'));
    }

    // GET /custody/create?evidence_id=5 — show transfer form
    public function create(Request $request)
    {
        $user = Auth::user();
        $role = $user->role->role_name ?? '';

        if ($role === 'Officer') {
            $evidenceItems = Evidence::whereHas('case', function($q) use ($user) {
                $q->where('officer_id', $user->user_id);
            })->get();
        } elseif ($role === 'Analyst') {
            $evidenceItems = Evidence::whereHas('testRequests', function($q) use ($user) {
                $q->where('assigned_analyst_id', $user->user_id);
            })->get();
        } else {
            $evidenceItems = Evidence::all();
        }

        $users = User::all();
        $selectedEvidenceId = $request->query('evidence_id');

        return view('custody.create', compact('evidenceItems', 'users', 'selectedEvidenceId'));
    }

    // POST /custody — record a new transfer
    public function store(Request $request)
    {
        $user = Auth::user();
        $role = $user->role->role_name ?? '';

        $validated = $request->validate([
            'evidence_id'  => 'required|exists:evidence,evidence_id',
            'from_user_id' => 'nullable|exists:users,user_id',
            'to_user_id'   => 'required|exists:users,user_id',
            'reason'       => 'required|string|max:255',
            'remarks'      => 'nullable|string|max:500',
        ]);

        $evidence = Evidence::findOrFail($validated['evidence_id']);

        if ($role === 'Officer') {
            if ($evidence->case->officer_id !== $user->user_id) {
                abort(403, 'You are not authorized to transfer this evidence.');
            }
        } elseif ($role === 'Analyst') {
            $hasAssignedTest = $evidence->testRequests()
                ->where('assigned_analyst_id', $user->user_id)
                ->exists();
            if (!$hasAssignedTest) {
                abort(403, 'You are not authorized to transfer this evidence.');
            }
        }

        $validated['transferred_by'] = $user->user_id ?? $validated['to_user_id'];

        CustodyRecord::create($validated);

        return redirect()->route('custody.index')->with('success', 'Custody transfer recorded successfully.');
    }
}