<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShortUrlController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Short URLs Management
    Route::get('/short-urls', [ShortUrlController::class, 'index'])->name('short-urls.index');
    Route::get('/short-urls/create', [ShortUrlController::class, 'create'])->name('short-urls.create');
    Route::post('/short-urls', [ShortUrlController::class, 'store'])->name('short-urls.store');

    Route::post('/invitations', [InvitationController::class, 'store'])->name('invitations.store');

    Route::resource('companies', CompanyController::class)->only(['index', 'create', 'store']);

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Invitation Acceptance Routes
Route::get('/invitations/{token}/accept', [InvitationController::class, 'showAccept'])->name('invitations.accept');
Route::post('/invitations/{token}/accept', [InvitationController::class, 'processAccept'])->name('invitations.process-accept');

require __DIR__.'/auth.php';

// Public Short URL Resolution Route (placed last so it doesn't collide with app routes)
Route::get('/{code}', [ShortUrlController::class, 'resolve'])
    ->where('code', '[a-zA-Z0-9_\-]+')
    ->name('short-urls.resolve');
