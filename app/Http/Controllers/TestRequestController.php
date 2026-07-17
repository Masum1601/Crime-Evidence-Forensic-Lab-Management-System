<?php

namespace App\Http\Controllers;

use App\Models\TestRequest;
use App\Models\Evidence;
use App\Models\ForensicTestType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestRequestController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = $user->role->role_name ?? '';

        $query = TestRequest::with(['evidence', 'testType', 'requestedBy', 'analyst']);

        if ($role === 'Officer') {
            $query->whereHas('evidence.case', function($q) use ($user) {
                $q->where('officer_id', $user->user_id);
            });
        } elseif ($role === 'Analyst') {
            $query->where('assigned_analyst_id', $user->user_id);
        }

        $requests = $query->orderBy('request_id', 'desc')->paginate(10);
        return view('tests.index', compact('requests'));
    }

    public function create()
    {
        $user = Auth::user();
        $role = $user->role->role_name ?? '';

        if ($role === 'Analyst') {
            abort(403, 'Analysts are not authorized to submit test requests.');
        }

        if ($role === 'Officer') {
            $evidenceItems = Evidence::whereHas('case', function($q) use ($user) {
                $q->where('officer_id', $user->user_id);
            })->get();
        } else {
            $evidenceItems = Evidence::all();
        }

        $testTypes = ForensicTestType::all();
        $analysts = User::whereHas('role', fn($q) => $q->where('role_name', 'Analyst'))->get();
        return view('tests.create', compact('evidenceItems', 'testTypes', 'analysts'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $role = $user->role->role_name ?? '';

        if ($role === 'Analyst') {
            abort(403, 'Analysts are not authorized to submit test requests.');
        }

        $validated = $request->validate([
            'evidence_id'         => 'required|exists:evidence,evidence_id',
            'test_type_id'        => 'required|exists:forensic_test_types,test_type_id',
            'assigned_analyst_id' => 'nullable|exists:users,user_id',
            'priority'            => 'required|in:LOW,NORMAL,HIGH,URGENT',
            'notes'               => 'nullable|string|max:500',
        ]);

        $evidence = Evidence::findOrFail($validated['evidence_id']);
        if ($role === 'Officer') {
            if ($evidence->case->officer_id !== $user->user_id) {
                abort(403, 'You are not authorized to submit test requests for this evidence.');
            }
        }

        $validated['requested_by'] = $user->user_id;
        $validated['test_status'] = 'PENDING';

        TestRequest::create($validated);

        return redirect()->route('tests.index')->with('success', 'Test request submitted.');
    }

    public function update(Request $request, TestRequest $test)
    {
        $user = Auth::user();
        $role = $user->role->role_name ?? '';

        if ($role === 'Officer') {
            if ($test->evidence->case->officer_id !== $user->user_id) {
                abort(403, 'You are not authorized to update this test request.');
            }
        } elseif ($role === 'Analyst') {
            if ($test->assigned_analyst_id !== $user->user_id) {
                abort(403, 'You are not authorized to update this test request.');
            }
        }

        $validated = $request->validate([
            'test_status'         => 'required|in:PENDING,IN_PROGRESS,COMPLETED,CANCELLED',
            'assigned_analyst_id' => 'nullable|exists:users,user_id',
            'notes'               => 'nullable|string|max:500',
        ]);

        // Analysts cannot reassign the analyst or notes, only status
        if ($role === 'Analyst') {
            unset($validated['assigned_analyst_id']);
            unset($validated['notes']);
        }

        $test->update($validated);
        return back()->with('success', 'Test request updated.');
    }
}