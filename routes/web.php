<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CaseController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EvidenceController;
use App\Http\Controllers\CustodyController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Week 1: Users + Cases CRUD (proved Laravel <-> Oracle connection)
| Week 2: + Login/Logout, Dashboard, Evidence CRUD, Chain of Custody
*/

// Public landing page
Route::get('/', function () {
    return view('welcome');
});

// ---- Authentication (Week 2) ----
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ---- Routes that require login ----
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('users', UserController::class);
    Route::resource('cases', CaseController::class);
    Route::resource('evidence', EvidenceController::class);

    Route::get('/custody', [CustodyController::class, 'index'])->name('custody.index');
    Route::get('/custody/create', [CustodyController::class, 'create'])->name('custody.create');
    Route::post('/custody', [CustodyController::class, 'store'])->name('custody.store');
});