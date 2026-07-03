<?php

namespace App\Http\Controllers;

use App\Models\CaseModel;
use App\Models\Evidence;
use App\Models\User;
use App\Models\CustodyRecord;

class DashboardController extends Controller
{
    // GET /dashboard — home page with live stats cards
    public function index()
    {
        $stats = [
            'total_cases'      => CaseModel::count(),
            'open_cases'       => CaseModel::where('case_status', 'OPEN')->count(),
            'closed_cases'     => CaseModel::where('case_status', 'CLOSED')->count(),
            'total_evidence'   => Evidence::count(),
            'evidence_in_storage' => Evidence::where('current_status', 'IN_STORAGE')->count(),
            'total_users'      => User::count(),
            'total_custody_transfers' => CustodyRecord::count(),
        ];

        $recentCases = CaseModel::with('officer')->orderBy('case_id', 'desc')->limit(5)->get();
        $recentEvidence = Evidence::with('case')->orderBy('evidence_id', 'desc')->limit(5)->get();

        return view('dashboard.index', compact('stats', 'recentCases', 'recentEvidence'));
    }
}