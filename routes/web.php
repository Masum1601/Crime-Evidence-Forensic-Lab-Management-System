<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\RoleRequestController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CaseController;
use App\Http\Controllers\EvidenceController;
use App\Http\Controllers\CustodyController;
use App\Http\Controllers\PublicSubmissionController;
use App\Http\Controllers\TestRequestController;
use App\Http\Controllers\CourtSubmissionController;
use App\Http\Controllers\TestReportController;
use App\Http\Controllers\EquipmentController;

// -------------------------------------------------------
// PUBLIC (no login)
// -------------------------------------------------------
Route::get('/', fn() => view('public.landing'))->name('home');

Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.store');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// -------------------------------------------------------
// AUTHENTICATED (any role, incl. plain "User")
// -------------------------------------------------------
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Public submission NOW requires login (prompt 6 will formalize this further)
    Route::get('/submit', [PublicSubmissionController::class, 'create'])->name('public.submit');
    Route::post('/submit', [PublicSubmissionController::class, 'store'])->name('public.submit.store');

    // Role request — any plain "User" can ask to become Officer/Analyst
    Route::get('/role-request', [RoleRequestController::class, 'create'])->name('role-request.create');
    Route::post('/role-request', [RoleRequestController::class, 'store'])->name('role-request.store');

    // Staff-only features (still visible to Officer/Analyst/Admin — "User" role
    // simply won't see these links per the dashboard prompt #5)
    Route::resource('cases', CaseController::class);
    Route::resource('evidence', EvidenceController::class);
    Route::get('/custody', [CustodyController::class, 'index'])->name('custody.index');
    Route::get('/custody/create', [CustodyController::class, 'create'])->name('custody.create');
    Route::post('/custody', [CustodyController::class, 'store'])->name('custody.store');
    Route::resource('tests', TestRequestController::class)->only(['index', 'create', 'store']);
    Route::put('/tests/{test}', [TestRequestController::class, 'update'])->name('tests.update');
});

// -------------------------------------------------------
// ADMIN only
// -------------------------------------------------------
Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::resource('users', UserController::class);
    Route::get('/admin/submissions', [PublicSubmissionController::class, 'index'])->name('admin.submissions');
    Route::post('/admin/submissions/{submission}/review', [PublicSubmissionController::class, 'review'])->name('admin.submissions.review');

    Route::get('/admin/role-requests', [RoleRequestController::class, 'index'])->name('admin.role-requests');
    Route::post('/admin/role-requests/{roleRequest}/decide', [RoleRequestController::class, 'decide'])->name('admin.role-requests.decide');
    Route::post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::post('/users/{user}/change-role', [UserController::class, 'changeRole'])->name('users.change-role');

    Route::get('/equipment/create', [EquipmentController::class, 'create'])->name('equipment.create');
    Route::post('/equipment', [EquipmentController::class, 'store'])->name('equipment.store');
});

// -------------------------------------------------------
// ADMIN, OFFICER, ANALYST
// -------------------------------------------------------
Route::middleware(['auth', 'role:Admin,Officer,Analyst'])->group(function () {
    Route::get('/court', [CourtSubmissionController::class, 'index'])->name('court.index');
    Route::get('/court/create', [CourtSubmissionController::class, 'create'])->name('court.create');
    Route::post('/court', [CourtSubmissionController::class, 'store'])->name('court.store');
    Route::put('/court/{court}', [CourtSubmissionController::class, 'update'])->name('court.update');

    Route::get('/equipment', [EquipmentController::class, 'index'])->name('equipment.index');
});

// -------------------------------------------------------
// ADMIN, ANALYST
// -------------------------------------------------------
Route::middleware(['auth', 'role:Admin,Analyst'])->group(function () {
    Route::get('/tests/{test}/report', [TestReportController::class, 'create'])->name('tests.report.create');
    Route::post('/tests/{test}/report', [TestReportController::class, 'store'])->name('tests.report.store');

    Route::post('/equipment/{equipment}/use', [EquipmentController::class, 'logUsage'])->name('equipment.use');
    Route::post('/equipment/{equipment}/release', [EquipmentController::class, 'release'])->name('equipment.release');
});