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
        $query = TestRequest::with(['evidence', 'testType', 'requestedBy', 'analyst']);

        // Officers/Analysts only see their own requests
        $role = Auth::user()->role->role_name ?? '';
        if ($role !== 'Admin') {
            $query->where(function($q) {
                $q->where('requested_by', Auth::id())
                  ->orWhere('assigned_analyst_id', Auth::id());
            });
        }

        $requests = $query->orderBy('request_id', 'desc')->paginate(10);
        return view('tests.index', compact('requests'));
    }

    public function create()
    {
        $evidenceItems = Evidence::all();
        $testTypes = ForensicTestType::all();
        $analysts = User::whereHas('role', fn($q) => $q->where('role_name', 'Analyst'))->get();
        return view('tests.create', compact('evidenceItems', 'testTypes', 'analysts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'evidence_id'         => 'required|exists:evidence,evidence_id',
            'test_type_id'        => 'required|exists:forensic_test_types,test_type_id',
            'assigned_analyst_id' => 'nullable|exists:users,user_id',
            'priority'            => 'required|in:LOW,NORMAL,HIGH,URGENT',
            'notes'               => 'nullable|string|max:500',
        ]);

        $validated['requested_by'] = Auth::id();
        $validated['test_status'] = 'PENDING';

        TestRequest::create($validated);

        return redirect()->route('tests.index')->with('success', 'Test request submitted.');
    }

    public function update(Request $request, TestRequest $test)
    {
        $validated = $request->validate([
            'test_status'         => 'required|in:PENDING,IN_PROGRESS,COMPLETED,CANCELLED',
            'assigned_analyst_id' => 'nullable|exists:users,user_id',
            'notes'               => 'nullable|string|max:500',
        ]);

        $test->update($validated);
        return back()->with('success', 'Test request updated.');
    }
}