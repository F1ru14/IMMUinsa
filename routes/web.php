<?php

use App\Http\Controllers\BiodataController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FasilitatorController;
use App\Http\Controllers\KaderJourneyController;
use App\Services\PersyarikatanNews;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

Route::get('/', function (PersyarikatanNews $news): Response {
    return Inertia::render('welcome', [
        'news' => $news->latest(),
    ]);
})->name('home');

Route::middleware(['auth'])->group(function (): void {
    // Biodata Onboarding (for Kader & Fasilitator)
    Route::middleware(['biodata.complete'])->prefix('biodata')->name('biodata.')->group(function (): void {
        Route::get('/complete', [BiodataController::class, 'create'])->name('create');
        Route::post('/complete', [BiodataController::class, 'store'])->name('store');
    });

    // Central Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware(['biodata.complete'])
        ->name('dashboard');

    // Role-specific Dashboards & Journeys
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function (): void {
        Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');
        Route::patch('/users/{user}/approve', [DashboardController::class, 'approveUser'])->name('users.approve');
        Route::delete('/users/{user}', [DashboardController::class, 'rejectUser'])->name('users.reject');
    });

    Route::middleware(['role:mentor', 'biodata.complete'])->prefix('mentor')->name('mentor.')->group(function (): void {
        Route::get('/dashboard', [DashboardController::class, 'mentor'])->name('dashboard');
    });

    Route::middleware(['role:fasilitator', 'biodata.complete'])->prefix('fasilitator')->name('fasilitator.')->group(function (): void {
        Route::get('/dashboard', [DashboardController::class, 'fasilitator'])->name('dashboard');
        Route::post('/kader/{kader}/evaluate', [FasilitatorController::class, 'storeEvaluation'])->name('kader.evaluate');
    });

    // Kader Journey Routes (Requires role:kader & biodata completed)
    Route::middleware(['role:kader', 'biodata.complete'])->prefix('kader')->name('kader.')->group(function (): void {
        Route::get('/dashboard', [DashboardController::class, 'kader'])->name('dashboard');

        // Step 1: Pilih 4 Fokus Bidang
        Route::get('/focus-area', [KaderJourneyController::class, 'showFocusArea'])->name('focus-area.create');
        Route::post('/focus-area', [KaderJourneyController::class, 'saveFocusArea'])->name('focus-area.store');

        // Step 2: Pilih Fasilitator berdasarkan Fokus Bidang
        Route::get('/select-fasilitator', [KaderJourneyController::class, 'showFasilitator'])->name('fasilitator.create');
        Route::post('/select-fasilitator', [KaderJourneyController::class, 'saveFasilitator'])->name('fasilitator.store');

        // Step 3: Pengumpulan / Penyelesaian Task (Minimal 3)
        Route::post('/tasks/{task}/submit', [KaderJourneyController::class, 'submitTask'])->name('tasks.submit');

        // Step 4: Unlock & Pilih Mentor
        Route::get('/select-mentor', [KaderJourneyController::class, 'showMentor'])->name('mentor.create');
        Route::post('/select-mentor', [KaderJourneyController::class, 'saveMentor'])->name('mentor.store');
    });
});

require __DIR__.'/settings.php';
