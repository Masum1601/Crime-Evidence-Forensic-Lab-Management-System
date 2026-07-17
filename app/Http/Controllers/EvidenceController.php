<?php

namespace App\Http\Controllers;

use App\Models\Evidence;
use App\Models\CaseModel;
use App\Models\User;
use App\Models\StorageLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvidenceController extends Controller
{
    // GET /evidence — list with search + pagination
    public function index(Request $request)
    {
        $user = Auth::user();
        $role = $user->role->role_name ?? '';

        $query = Evidence::with(['case', 'collector', 'location']);

        if ($role === 'Officer') {
            $query->whereHas('case', function($q) use ($user) {
                $q->where('officer_id', $user->user_id);
            });
        } elseif ($role === 'Analyst') {
            $query->whereHas('testRequests', function($q) use ($user) {
                $q->where('assigned_analyst_id', $user->user_id);
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('evidence_name', 'like', "%{$search}%")
                  ->orWhere('evidence_type', 'like', "%{$search}%")
                  ->orWhere('barcode_no', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('current_status', $request->status);
        }

        $evidenceItems = $query->orderBy('evidence_id', 'desc')->paginate(10)->withQueryString();

        return view('evidence.index', compact('evidenceItems'));
    }

    // GET /evidence/create
    public function create()
    {
        $user = Auth::user();
        $role = $user->role->role_name ?? '';

        if ($role === 'Analyst') {
            abort(403, 'Analysts are not authorized to register evidence.');
        }

        if ($role === 'Officer') {
            $cases = CaseModel::where('officer_id', $user->user_id)->get();
        } else {
            $cases = CaseModel::all();
        }

        $users = User::all();
        $locations = StorageLocation::all();
        return view('evidence.create', compact('cases', 'users', 'locations'));
    }

    // POST /evidence
    public function store(Request $request)
    {
        $user = Auth::user();
        $role = $user->role->role_name ?? '';

        if ($role === 'Analyst') {
            abort(403, 'Analysts are not authorized to register evidence.');
        }

        $validated = $request->validate([
            'case_id'         => 'required|exists:cases,case_id',
            'collected_by'    => 'required|exists:users,user_id',
            'location_id'     => 'nullable|exists:storage_locations,location_id',
            'evidence_name'   => 'required|string|max:150',
            'evidence_type'   => 'nullable|string|max:50',
            'description'     => 'nullable|string|max:1000',
            'current_status'  => 'required|in:IN_STORAGE,IN_ANALYSIS,IN_TRANSIT,RELEASED,DISPOSED',
            'barcode_no'      => 'nullable|string|max:50|unique:evidence,barcode_no',
        ]);

        if ($role === 'Officer') {
            $case = CaseModel::findOrFail($validated['case_id']);
            if ($case->officer_id !== $user->user_id) {
                abort(403, 'You can only register evidence for cases assigned to you.');
            }
        }

        $evidence = Evidence::create($validated);

        // Automatically create the first custody record (initial collection)
        \App\Models\CustodyRecord::create([
            'evidence_id'    => $evidence->evidence_id,
            'from_user_id'   => null,
            'to_user_id'     => $evidence->collected_by,
            'transferred_by' => $evidence->collected_by,
            'reason'         => 'Initial collection',
            'remarks'        => 'Evidence registered into the system',
        ]);

        return redirect()->route('evidence.index')->with('success', 'Evidence registered successfully.');
    }

    // GET /evidence/{id} — view evidence detail + full custody history
    public function show(Evidence $evidence)
    {
        $user = Auth::user();
        $role = $user->role->role_name ?? '';

        if ($role === 'Officer') {
            if ($evidence->case->officer_id !== $user->user_id) {
                abort(403, 'You are not authorized to view this evidence.');
            }
        } elseif ($role === 'Analyst') {
            $hasAssignedTest = $evidence->testRequests()
                ->where('assigned_analyst_id', $user->user_id)
                ->exists();
            if (!$hasAssignedTest) {
                abort(403, 'You are not authorized to view this evidence.');
            }
        }

        $evidence->load(['case', 'collector', 'location', 'custodyRecords.fromUser', 'custodyRecords.toUser']);
        return view('evidence.show', compact('evidence'));
    }

    // GET /evidence/{id}/edit
    public function edit(Evidence $evidence)
    {
        $user = Auth::user();
        $role = $user->role->role_name ?? '';

        if ($role === 'Analyst') {
            abort(403, 'Analysts are not authorized to edit evidence.');
        }

        if ($role === 'Officer') {
            if ($evidence->case->officer_id !== $user->user_id) {
                abort(403, 'You are not authorized to edit this evidence.');
            }
            $cases = CaseModel::where('officer_id', $user->user_id)->get();
        } else {
            $cases = CaseModel::all();
        }

        $users = User::all();
        $locations = StorageLocation::all();
        return view('evidence.edit', compact('evidence', 'cases', 'users', 'locations'));
    }

    // PUT /evidence/{id}
    public function update(Request $request, Evidence $evidence)
    {
        $user = Auth::user();
        $role = $user->role->role_name ?? '';

        if ($role === 'Analyst') {
            abort(403, 'Analysts are not authorized to modify evidence.');
        }

        if ($role === 'Officer') {
            if ($evidence->case->officer_id !== $user->user_id) {
                abort(403, 'You are not authorized to modify this evidence.');
            }
        }

        $validated = $request->validate([
            'case_id'         => 'required|exists:cases,case_id',
            'collected_by'    => 'required|exists:users,user_id',
            'location_id'     => 'nullable|exists:storage_locations,location_id',
            'evidence_name'   => 'required|string|max:150',
            'evidence_type'   => 'nullable|string|max:50',
            'description'     => 'nullable|string|max:1000',
            'current_status'  => 'required|in:IN_STORAGE,IN_ANALYSIS,IN_TRANSIT,RELEASED,DISPOSED',
            'barcode_no'      => 'nullable|string|max:50|unique:evidence,barcode_no,' . $evidence->evidence_id . ',evidence_id',
        ]);

        if ($role === 'Officer') {
            $case = CaseModel::findOrFail($validated['case_id']);
            if ($case->officer_id !== $user->user_id) {
                abort(403, 'You can only assign evidence to cases assigned to you.');
            }
        }

        $evidence->update($validated);

        return redirect()->route('evidence.index')->with('success', 'Evidence updated successfully.');
    }

    // DELETE /evidence/{id}
    public function destroy(Evidence $evidence)
    {
        $user = Auth::user();
        $role = $user->role->role_name ?? '';

        if ($role === 'Analyst') {
            abort(403, 'Analysts are not authorized to delete evidence.');
        }

        if ($role === 'Officer') {
            if ($evidence->case->officer_id !== $user->user_id) {
                abort(403, 'You are not authorized to delete this evidence.');
            }
        }

        $evidence->delete();
        return redirect()->route('evidence.index')->with('success', 'Evidence deleted successfully.');
    }
}