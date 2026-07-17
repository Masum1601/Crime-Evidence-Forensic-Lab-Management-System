<?php

namespace App\Http\Controllers;

use App\Models\CaseModel;
use App\Models\Evidence;
use App\Models\User;
use App\Models\CustodyRecord;
use App\Models\RoleRequest;
use App\Models\PublicSubmission;
use App\Models\TestRequest;
use App\Models\Equipment;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    // GET /dashboard — routes users to their respective role-based dashboard
    public function index()
    {
        $user = Auth::user();
        $role = $user->role->role_name ?? 'User';

        if ($role === 'Admin') {
            $stats = [
                'total_users'             => User::count(),
                'total_cases'             => CaseModel::count(),
                'total_evidence'          => Evidence::count(),
                'pending_role_requests'   => RoleRequest::where('status', 'PENDING')->count(),
                'pending_submissions'     => PublicSubmission::where('status', 'PENDING')->count(),
            ];

            $recentRoleRequests = RoleRequest::with(['user', 'requestedRole'])
                ->orderBy('request_id', 'desc')
                ->limit(5)
                ->get();

            $recentSubmissions = PublicSubmission::orderBy('submission_id', 'desc')
                ->limit(5)
                ->get();

            return view('dashboard.admin', compact('stats', 'recentRoleRequests', 'recentSubmissions'));
        }

        if ($role === 'Officer') {
            $stats = [
                'my_cases'          => CaseModel::where('officer_id', $user->user_id)->count(),
                'my_open_cases'     => CaseModel::where('officer_id', $user->user_id)->where('case_status', 'OPEN')->count(),
                'my_closed_cases'   => CaseModel::where('officer_id', $user->user_id)->where('case_status', 'CLOSED')->count(),
                'my_evidence'       => Evidence::whereHas('case', fn($q) => $q->where('officer_id', $user->user_id))->count(),
            ];

            $recentCases = CaseModel::where('officer_id', $user->user_id)
                ->orderBy('case_id', 'desc')
                ->limit(5)
                ->get();

            $recentEvidence = Evidence::whereHas('case', fn($q) => $q->where('officer_id', $user->user_id))
                ->orderBy('evidence_id', 'desc')
                ->limit(5)
                ->get();

            return view('dashboard.officer', compact('stats', 'recentCases', 'recentEvidence'));
        }

        if ($role === 'Analyst') {
            $stats = [
                'my_assigned_tests' => TestRequest::where('assigned_analyst_id', $user->user_id)->count(),
                'my_pending_tests'  => TestRequest::where('assigned_analyst_id', $user->user_id)->where('test_status', 'PENDING')->count(),
                'my_progress_tests' => TestRequest::where('assigned_analyst_id', $user->user_id)->where('test_status', 'IN_PROGRESS')->count(),
                'my_completed_tests'=> TestRequest::where('assigned_analyst_id', $user->user_id)->where('test_status', 'COMPLETED')->count(),
            ];

            $assignedTests = TestRequest::with(['evidence', 'testType'])
                ->where('assigned_analyst_id', $user->user_id)
                ->orderBy('request_id', 'desc')
                ->limit(5)
                ->get();

            $equipments = Equipment::limit(5)->get();

            return view('dashboard.analyst', compact('stats', 'assignedTests', 'equipments'));
        }

        // Default: plain citizen "User" role
        $stats = [
            'my_submissions_count' => PublicSubmission::where('submitted_by', $user->user_id)->count(),
            'my_role_requests_count' => RoleRequest::where('user_id', $user->user_id)->count(),
        ];

        $mySubmissions = PublicSubmission::where('submitted_by', $user->user_id)
            ->orderBy('submission_id', 'desc')
            ->limit(5)
            ->get();

        $myRequests = RoleRequest::with('requestedRole')
            ->where('user_id', $user->user_id)
            ->orderBy('request_id', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard.user', compact('stats', 'mySubmissions', 'myRequests'));
    }
}