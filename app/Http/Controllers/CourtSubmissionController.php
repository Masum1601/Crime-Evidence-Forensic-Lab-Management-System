<?php

namespace App\Http\Controllers;

use App\Models\CourtSubmission;
use App\Models\Evidence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourtSubmissionController extends Controller
{
    public function index()
    {
        $submissions = CourtSubmission::with(['evidence', 'submittedByUser'])
            ->orderBy('submission_id', 'desc')->paginate(10);
        return view('court.index', compact('submissions'));
    }

    public function create()
    {
        $evidenceItems = Evidence::whereIn('current_status', ['IN_STORAGE', 'IN_ANALYSIS'])->get();
        return view('court.create', compact('evidenceItems'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'evidence_id'       => 'required|exists:evidence,evidence_id',
            'court_name'        => 'required|string|max:150',
            'case_reference_no' => 'nullable|string|max:100',
            'remarks'           => 'nullable|string|max:500',
        ]);

        $validated['submitted_by'] = Auth::id();
        CourtSubmission::create($validated);

        return redirect()->route('court.index')->with('success', 'Evidence submitted to court and logged.');
    }

    public function update(Request $request, CourtSubmission $court)
    {
        $validated = $request->validate([
            'status'      => 'required|in:SUBMITTED,RETURNED,RETAINED',
            'return_date' => 'nullable|date',
        ]);

        $court->update($validated);

        if ($validated['status'] === 'RETURNED') {
            $court->evidence->update(['current_status' => 'IN_STORAGE']);
        }

        return back()->with('success', 'Court submission updated.');
    }
}