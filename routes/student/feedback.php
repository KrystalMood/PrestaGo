<?php

use App\Http\Controllers\Student\CompetitionFeedbackController;
use Illuminate\Support\Facades\Route;

// Competition Feedback Routes
Route::prefix('feedback')->name('feedback.')->middleware(['auth', 'role:student'])->group(function () {
    Route::get('/', [CompetitionFeedbackController::class, 'index'])->name('index');
    Route::post('/', [CompetitionFeedbackController::class, 'store'])->name('store');
    Route::get('/{id}', [CompetitionFeedbackController::class, 'show'])->name('show');
    Route::delete('/{id}', [CompetitionFeedbackController::class, 'destroy'])->name('destroy');
    
    // AJAX route to list feedback
    Route::get('/list', [CompetitionFeedbackController::class, 'list'])->name('list');
    
    // Check if student can provide feedback for a competition
    Route::post('/check-eligibility', [CompetitionFeedbackController::class, 'checkFeedbackEligibility'])->name('check-eligibility');
}); 