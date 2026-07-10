<?php

namespace App\Http\Controllers;

use App\Models\PublicSubmission;
use App\Models\CaseModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PublicSubmissionController extends Controller
{
    // GET /submit — public form (no login needed)
    public function create()
    {
        $cases = CaseModel::where('case_status', 'OPEN')->get();
        return view('public.submit', compact('cases'));
    }

    // POST /submit — store submission (no login needed)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'submitter_name'  => 'required|string|max:100',
            'submitter_email' => 'nullable|email|max:100',
            'submitter_phone' => 'nullable|string|max:20',
            'subject'         => 'required|string|max:200',
            'description'     => 'required|string|max:2000',
            'related_case_id' => 'nullable|exists:cases,case_id',
        ]);

        PublicSubmission::create($validated);

        return redirect()->route('public.submit')->with('success',
            'Your submission has been received. Thank you for your information.');
    }

    // GET /admin/submissions — admin view of all submissions
    public function index(Request $request)
    {
        $query = PublicSubmission::with(['relatedCase', 'reviewer']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $submissions = $query->orderBy('submission_id', 'desc')->paginate(10)->withQueryString();
        return view('admin.submissions.index', compact('submissions'));
    }

    // POST /admin/submissions/{id}/review — mark as reviewed
    public function review(Request $request, PublicSubmission $submission)
    {
        $validated = $request->validate([
            'status'       => 'required|in:REVIEWED,DISMISSED',
            'review_notes' => 'nullable|string|max:500',
        ]);

        $submission->update([
            'status'       => $validated['status'],
            'review_notes' => $validated['review_notes'],
            'reviewed_by'  => Auth::id(),
        ]);

        return back()->with('success', 'Submission reviewed successfully.');
    }
}