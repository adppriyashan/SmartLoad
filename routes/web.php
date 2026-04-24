<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoanRequestController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Redirect::to('/login');
});

Auth::routes();

Route::get('/api/loans/latest-in-progress', [LoanRequestController::class, 'getLatestInProgress'])->name('loans.api.latest');
Route::get('/api/loans/decision', [LoanRequestController::class, 'apiUpdateDecision'])->name('loans.api.decision');


Route::middleware(['auth'])->group(function () {
    // Profile Completion Routes
    Route::get('/profile/complete', [ProfileController::class, 'showCompleteForm'])->name('profile.complete');
    Route::post('/profile/complete', [ProfileController::class, 'updateProfile'])->name('profile.update');

    // Protected Routes (Require Profile Completion)
    Route::middleware(['profile.complete'])->group(function () {
        Route::get('/home', [HomeController::class, 'index'])->name('home');
        Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');

        // Dashboard Route (Open to all users for now)
        Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

        // Loan Request Routes
        Route::get('/loans/request', [LoanRequestController::class, 'create'])->name('loans.request');
        Route::post('/loans/request', [LoanRequestController::class, 'store'])->name('loans.store');
        Route::get('/loans/my-loans', [LoanRequestController::class, 'index'])->name('loans.index');
        Route::get('/loans/{loan}', [LoanRequestController::class, 'show'])->name('loans.show');
        Route::post('/loans/{loan}/status', [LoanRequestController::class, 'updateStatus'])->name('loans.updateStatus');
        Route::post('/loans/{loan}/financials', [LoanRequestController::class, 'updateFinancials'])->name('loans.updateFinancials');
    });
});
