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
        $user = Auth::user();
        $role = $user->role->role_name ?? '';

        $query = CourtSubmission::with(['evidence', 'submittedByUser']);

        if ($role === 'Officer') {
            $query->whereHas('evidence.case', function($q) use ($user) {
                $q->where('officer_id', $user->user_id);
            });
        } elseif ($role === 'Analyst') {
            $query->whereHas('evidence.testRequests', function($q) use ($user) {
                $q->where('assigned_analyst_id', $user->user_id);
            });
        }

        $submissions = $query->orderBy('submission_id', 'desc')->paginate(10);
        return view('court.index', compact('submissions'));
    }

    public function create()
    {
        $user = Auth::user();
        $role = $user->role->role_name ?? '';

        if ($role === 'Analyst') {
            abort(403, 'Analysts are not authorized to make court submissions.');
        }

        if ($role === 'Officer') {
            $evidenceItems = Evidence::whereHas('case', function($q) use ($user) {
                $q->where('officer_id', $user->user_id);
            })->whereIn('current_status', ['IN_STORAGE', 'IN_ANALYSIS'])->get();
        } else {
            $evidenceItems = Evidence::whereIn('current_status', ['IN_STORAGE', 'IN_ANALYSIS'])->get();
        }

        return view('court.create', compact('evidenceItems'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $role = $user->role->role_name ?? '';

        if ($role === 'Analyst') {
            abort(403, 'Analysts are not authorized to make court submissions.');
        }

        $validated = $request->validate([
            'evidence_id'       => 'required|exists:evidence,evidence_id',
            'court_name'        => 'required|string|max:150',
            'case_reference_no' => 'nullable|string|max:100',
            'remarks'           => 'nullable|string|max:500',
        ]);

        $evidence = Evidence::findOrFail($validated['evidence_id']);
        if ($role === 'Officer') {
            if ($evidence->case->officer_id !== $user->user_id) {
                abort(403, 'You are not authorized to submit this evidence to court.');
            }
        }

        $validated['submitted_by'] = $user->user_id;
        CourtSubmission::create($validated);

        return redirect()->route('court.index')->with('success', 'Evidence submitted to court and logged.');
    }

    public function update(Request $request, CourtSubmission $court)
    {
        $user = Auth::user();
        $role = $user->role->role_name ?? '';

        if ($role === 'Officer') {
            if ($court->evidence->case->officer_id !== $user->user_id) {
                abort(403, 'You are not authorized to update this court submission.');
            }
        } elseif ($role === 'Analyst') {
            abort(403, 'Analysts are not authorized to update court submissions.');
        }

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