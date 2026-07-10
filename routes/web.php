<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CaseController;
use App\Http\Controllers\EvidenceController;
use App\Http\Controllers\CustodyController;
use App\Http\Controllers\PublicSubmissionController;
use App\Http\Controllers\TestRequestController;

// -------------------------------------------------------
// PUBLIC routes — no login required
// -------------------------------------------------------
Route::get('/', function () {
    return view('public.landing');
})->name('home');

Route::get('/submit', [PublicSubmissionController::class, 'create'])->name('public.submit');
Route::post('/submit', [PublicSubmissionController::class, 'store'])->name('public.submit.store');

// -------------------------------------------------------
// AUTH routes
// -------------------------------------------------------
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// -------------------------------------------------------
// AUTHENTICATED routes (all roles)
// -------------------------------------------------------
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('cases', CaseController::class);
    Route::resource('evidence', EvidenceController::class);
    Route::get('/custody', [CustodyController::class, 'index'])->name('custody.index');
    Route::get('/custody/create', [CustodyController::class, 'create'])->name('custody.create');
    Route::post('/custody', [CustodyController::class, 'store'])->name('custody.store');
    Route::resource('tests', TestRequestController::class)->only(['index', 'create', 'store']);
    Route::put('/tests/{test}', [TestRequestController::class, 'update'])->name('tests.update');
});

// -------------------------------------------------------
// ADMIN only routes
// -------------------------------------------------------
Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::resource('users', UserController::class);
    Route::get('/admin/submissions', [PublicSubmissionController::class, 'index'])->name('admin.submissions');
    Route::post('/admin/submissions/{submission}/review', [PublicSubmissionController::class, 'review'])->name('admin.submissions.review');
});