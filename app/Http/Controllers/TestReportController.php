<?php

namespace App\Http\Controllers;

use App\Models\TestReport;
use App\Models\TestRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestReportController extends Controller
{
    public function create(TestRequest $test)
    {
        $user = Auth::user();
        $role = $user->role->role_name ?? '';
        if ($role !== 'Admin' && $test->assigned_analyst_id !== $user->user_id) {
            abort(403, 'You are not authorized to file a report for this test request.');
        }

        return view('tests.report', compact('test'));
    }

    public function store(Request $request, TestRequest $test)
    {
        $user = Auth::user();
        $role = $user->role->role_name ?? '';
        if ($role !== 'Admin' && $test->assigned_analyst_id !== $user->user_id) {
            abort(403, 'You are not authorized to file a report for this test request.');
        }

        $validated = $request->validate([
            'result_summary'  => 'required|string|max:500',
            'detailed_report' => 'nullable|string|max:2000',
        ]);

        $validated['request_id'] = $test->request_id;
        $validated['verified_by'] = Auth::id();

        TestReport::create($validated);

        return redirect()->route('tests.index')->with('success', 'Test report filed and request marked complete.');
    }
}