<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ElectionController;
use App\Http\Controllers\Officer\PositionController;
use App\Http\Controllers\Officer\CandidateController;
use App\Http\Controllers\Voter\VoteController;
use App\Http\Controllers\Admin\ResultController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\CandidateApprovalController;
use App\Http\Controllers\Admin\AuditLogController;


Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {

    // ADMIN
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        // Elections
        Route::resource('elections', ElectionController::class)
            ->except(['show']);

        Route::patch('elections/{election}/activate', [ElectionController::class, 'activate'])
            ->name('elections.activate');
        
        Route::post('elections/{election}/start', [ElectionController::class, 'start'])
            ->name('elections.start');

        Route::patch('elections/{election}/close', [ElectionController::class, 'close'])
            ->name('elections.close');

        // Candidates (view all)
        Route::get('/candidates', [CandidateController::class, 'index'])
            ->name('candidates.index');

        // Candidate Approval
        Route::get('/candidates/approval', [CandidateApprovalController::class, 'index'])
            ->name('candidates.approval');

        Route::post('/candidates/{candidate}/approve', [CandidateApprovalController::class, 'approve'])
            ->name('candidates.approve');

        Route::post('/candidates/{candidate}/reject', [CandidateApprovalController::class, 'reject'])
            ->name('candidates.reject');

        // Results
        Route::get('/results', [ResultController::class, 'index'])
            ->name('results.index');

        Route::get('/results/{election}', [ResultController::class, 'show'])
            ->name('results.show');

        Route::get('/results/{election}/pdf', [ResultController::class, 'pdf'])
            ->name('results.pdf');

        Route::get('/admin/results/pdf', [ResultController::class, 'exportPdf'])
            ->name('admin.results.pdf');

        
        // Audit Logs
        Route::get('/audit-logs', [AuditLogController::class, 'index'])
        ->name('audit.logs');
    });



    // OFFICER
    Route::middleware('role:officer')
        ->prefix('officer')
        ->name('officer.')
        ->group(function () {
            Route::get('/dashboard', fn () => view('officer.dashboard'))->name('dashboard');

            Route::resource('positions', PositionController::class);
            Route::resource('candidates', CandidateController::class);

            Route::get('/results/{election}', [ResultController::class, 'show'])->name('results.show');
            
        });



    // VOTER
    Route::middleware('role:voter')
        ->prefix('voter')
        ->name('voter.')
        ->group(function () {
            Route::get('/dashboard', fn () => view('voter.dashboard'))->name('dashboard');

            Route::get('/vote', [VoteController::class, 'index'])->name('vote.index');
            Route::post('/vote', [VoteController::class, 'store'])->name('vote.store');
            Route::get('/vote/{election}', [VoteController::class, 'show'])->name('vote.show');
            Route::post('/vote', [VoteController::class, 'submit'])->name('vote.submit');

            Route::get('/results/{election}', [ResultController::class, 'show'])->name('results.show');

        });

    // PROFILE (ALL AUTH USERS)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

