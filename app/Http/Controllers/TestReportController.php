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
        return view('tests.report', compact('test'));
    }

    public function store(Request $request, TestRequest $test)
    {
        $validated = $request->validate([
            'result_summary'  => 'required|string|max:500',
            'detailed_report' => 'nullable|string|max:2000',
        ]);

        $validated['request_id'] = $test->request_id;
        $validated['verified_by'] = Auth::id();

        TestReport::create($validated);
        // trg_report_completes_test marks the request COMPLETED automatically

        return redirect()->route('tests.index')->with('success', 'Test report filed and request marked complete.');
    }
}