<?php

namespace App\Http\Controllers;

use App\Models\PublicSubmission;
use App\Models\CaseModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PublicSubmissionController extends Controller
{
    // GET /submit — now requires login (route already behind 'auth' middleware)
    public function create()
    {
        $cases = CaseModel::where('case_status', 'OPEN')->get();
        return view('public.submit', compact('cases'));
    }

    // POST /submit
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject'         => 'required|string|max:200',
            'description'     => 'required|string|max:2000',
            'related_case_id' => 'nullable|exists:cases,case_id',
        ]);

        $user = Auth::user();

        PublicSubmission::create([
            'submitter_name'  => $user->full_name,
            'submitter_email' => $user->email,
            'submitter_phone' => $user->phone,
            'subject'         => $validated['subject'],
            'description'     => $validated['description'],
            'related_case_id' => $validated['related_case_id'] ?? null,
            'submitted_by'    => $user->user_id,
            'status'          => 'PENDING',
        ]);

        return redirect()->route('public.submit')->with('success',
            'Your submission has been received and linked to your account. Thank you.');
    }

    // GET /admin/submissions
    public function index(Request $request)
    {
        $query = PublicSubmission::with(['relatedCase', 'reviewer', 'submittedByUser']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $submissions = $query->orderBy('submission_id', 'desc')->paginate(10)->withQueryString();
        return view('admin.submissions.index', compact('submissions'));
    }

    // POST /admin/submissions/{id}/review
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