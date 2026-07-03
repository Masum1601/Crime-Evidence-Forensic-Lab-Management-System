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
        $query = CustodyRecord::with(['evidence', 'fromUser', 'toUser', 'transferredByUser']);

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
        $evidenceItems = Evidence::all();
        $users = User::all();
        $selectedEvidenceId = $request->query('evidence_id');

        return view('custody.create', compact('evidenceItems', 'users', 'selectedEvidenceId'));
    }

    // POST /custody — record a new transfer
    public function store(Request $request)
    {
        $validated = $request->validate([
            'evidence_id'  => 'required|exists:evidence,evidence_id',
            'from_user_id' => 'nullable|exists:users,user_id',
            'to_user_id'   => 'required|exists:users,user_id',
            'reason'       => 'required|string|max:255',
            'remarks'      => 'nullable|string|max:500',
        ]);

        $validated['transferred_by'] = Auth::id() ?? $validated['to_user_id'];

        CustodyRecord::create($validated);

        // The trg_custody_audit PL/SQL trigger fires automatically
        // on insert and logs this transfer into AUDIT_LOGS.

        return redirect()->route('custody.index')->with('success', 'Custody transfer recorded successfully.');
    }
}