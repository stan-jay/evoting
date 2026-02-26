<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PublicResultController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CandidateController as AdminCandidateController;
use App\Http\Controllers\Admin\ElectionController;
use App\Http\Controllers\Officer\PositionController;
use App\Http\Controllers\Officer\CandidateController as OfficerCandidateController;
use App\Http\Controllers\Voter\VoteController;
use App\Http\Controllers\Voter\DashboardController as VoterDashboardController;
use App\Http\Controllers\Admin\AdminResultController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\CandidateApprovalController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\InviteController as AdminInviteController;
use App\Http\Controllers\SuperAdmin\OrganizationController as SuperAdminOrganizationController;
use App\Http\Controllers\SuperAdmin\UserInterventionController as SuperAdminUserInterventionController;
use App\Http\Controllers\SuperAdmin\DocumentationController as SuperAdminDocumentationController;


Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware(['auth', 'active'])->get('/dashboard', DashboardController::class)->name('dashboard');

Route::middleware(['auth', 'active'])->group(function () {
    // Backward-compatible voter shortcuts for legacy links.
    Route::middleware('role:voter')->group(function () {
        Route::get('/elections', fn () => redirect()->route('voter.vote.index'))->name('elections.index');
        Route::get('/elections/{election}', fn (\App\Models\Election $election) => redirect()->route('voter.vote.create', $election))->name('elections.show');
        Route::get('/results', fn () => redirect()->route('voter.results.index'))->name('results.index');
    });

    // SUPER ADMIN
    Route::middleware(['auth', 'role:super_admin'])
        ->prefix('super-admin')
        ->name('super_admin.')
        ->group(function () {
            Route::get('/dashboard', [SuperAdminOrganizationController::class, 'index'])->name('dashboard');
            Route::get('/organizations', [SuperAdminOrganizationController::class, 'index'])->name('organizations.index');
            Route::post('/organizations', [SuperAdminOrganizationController::class, 'store'])->name('organizations.store');
            Route::delete('/organizations/{organization}', [SuperAdminOrganizationController::class, 'destroy'])->name('organizations.destroy');
            Route::get('/users', [SuperAdminUserInterventionController::class, 'index'])->name('users.index');
            Route::put('/users/{user}', [SuperAdminUserInterventionController::class, 'update'])->name('users.update');
            Route::get('/docs', [SuperAdminDocumentationController::class, 'show'])->name('docs.show');
        });

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
        Route::get('/candidates', [AdminCandidateController::class, 'index'])
            ->name('candidates.index');

        // Candidate Approval
        Route::get('/candidates/approval', [CandidateApprovalController::class, 'index'])
            ->name('candidates.approval');

        Route::post('/candidates/{candidate}/approve', [CandidateApprovalController::class, 'approve'])
            ->name('candidates.approve');

        Route::post('/candidates/{candidate}/reject', [CandidateApprovalController::class, 'reject'])
            ->name('candidates.reject');

        // Results
        Route::get('/results', [AdminResultController::class, 'index'])
            ->name('results.index');

        Route::get('/results/{election}', [AdminResultController::class, 'show'])
            ->name('results.show');

        Route::get('/results/{election}/pdf', [AdminResultController::class, 'pdf'])
            ->name('results.pdf');

        Route::get('/results/{election}/excel', [AdminResultController::class, 'excel'])
            ->name('results.excel');

        Route::get('/results/{election}/live', [AdminResultController::class, 'live'])
            ->middleware('throttle:results-live')
            ->name('results.live');

        Route::patch('/results/{election}/publish', [AdminResultController::class, 'publish'])
            ->name('results.publish');

        Route::patch('/results/{election}/unpublish', [AdminResultController::class, 'unpublish'])
            ->name('results.unpublish');

        // Audit Logs
        Route::get('/audit-logs', [AuditLogController::class, 'index'])
        ->name('audit.logs');


        // Admin user management
        Route::get('users', [AdminUserController::class, 'index'])->name('users.index');
        Route::put('users/{user}', [AdminUserController::class, 'update'])->name('users.update');
        Route::delete('users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

        // Organization invites
        Route::get('invites', [AdminInviteController::class, 'index'])->name('invites.index');
        Route::post('invites', [AdminInviteController::class, 'store'])->middleware('throttle:invite-create')->name('invites.store');
        Route::post('invites/{invite}/resend', [AdminInviteController::class, 'resend'])->middleware('throttle:invite-create')->name('invites.resend');
    });



    // OFFICER
    Route::middleware('role:officer')
        ->prefix('officer')
        ->name('officer.')
        ->group(function () {
            Route::get('/dashboard', fn () => view('officer.dashboard'))->name('dashboard');

            Route::resource('positions', PositionController::class);
            Route::resource('candidates', OfficerCandidateController::class);

            Route::get('/results/{election}', [PublicResultController::class, 'showOfficer'])->name('results.show');
            Route::get('/results/{election}/live', [PublicResultController::class, 'liveOfficer'])->middleware('throttle:results-live')->name('results.live');
            
        });



    // VOTER
    Route::middleware('role:voter')
        ->prefix('voter')
        ->name('voter.')
        ->group(function () {
            Route::get('/dashboard', [VoterDashboardController::class, 'index'])->name('dashboard');

            Route::get('/vote', [VoteController::class, 'index'])->name('vote.index');
            Route::get('/vote/{election}', [VoteController::class, 'show'])->name('vote.create');
            Route::get('/vote/{election}/candidate/{candidate}', [VoteController::class, 'candidateProfile'])->name('vote.candidate.show');
            Route::post('/vote', [VoteController::class, 'submit'])->middleware('throttle:vote-submit')->name('vote.submit');

            Route::get('/results', [PublicResultController::class, 'indexVoter'])->name('results.index');
            Route::get('/results/{election}', [PublicResultController::class, 'showVoter'])->name('results.show');

        });

    // PROFILE (ALL AUTH USERS)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
