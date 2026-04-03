<?php

use App\Features\Admin\AdminController;
use App\Features\Auth\AuthController;
use App\Features\Bebe\BebeController;
use App\Features\Consultation\ConsultationController;
use App\Features\Famille\FamilleController;
use App\Features\Grossesse\GrossesseController;
use App\Features\RendezVous\RendezVousController;
use App\Features\Scan\ScanController;
use App\Features\User\UserController;
use App\Features\Vaccination\VaccinationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Auth publique
Route::prefix('auth')->group(function (): void {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});

Route::middleware('auth:api')->group(function (): void {
    // Auth
    Route::post('auth/logout', [AuthController::class, 'logout']);
    Route::get('auth/me', [AuthController::class, 'me']);
    Route::patch('auth/me', [AuthController::class, 'updateMe']);
    Route::put('auth/profile', [AuthController::class, 'updateMe']);
    Route::post('auth/change-password', [AuthController::class, 'changePassword']);
    Route::patch('auth/password', [AuthController::class, 'changePassword']);

    // Admin (aligné avec le front)
    Route::prefix('admin')->middleware('role:admin')->group(function (): void {
        Route::get('users', [AdminController::class, 'users']);
        Route::get('stats', [AdminController::class, 'stats']);
        Route::get('professionnels/pending', [AdminController::class, 'pendingProfessionnels']);
        Route::post('professionnels/{user}/approve', [AdminController::class, 'approveProfessionnel']);
        Route::post('professionnels/{user}/reject', [AdminController::class, 'rejectProfessionnel']);
        Route::patch('users/{user}/role', [AdminController::class, 'updateUserRole']);
        Route::patch('users/{user}/status', [AdminController::class, 'updateUserStatus']);
    });

    // CRUD utilisateurs (réservé admin)
    Route::middleware('role:admin')->group(function (): void {
        Route::get('users/{user}', [UserController::class, 'show']);
        Route::put('users/{user}', [UserController::class, 'update']);
        Route::delete('users/{user}', [UserController::class, 'destroy']);
    });

    // Métier (authentifié)
    Route::get('consultations', [ConsultationController::class, 'index']);
    Route::get('consultations/{consultation}', [ConsultationController::class, 'show']);
    Route::post('consultations', [ConsultationController::class, 'store']);
    Route::patch('consultations/{consultation}', [ConsultationController::class, 'update']);

    Route::get('grossesses', [GrossesseController::class, 'index']);
    Route::get('grossesses/{grossesse}', [GrossesseController::class, 'show']);
    Route::post('grossesses', [GrossesseController::class, 'store']);
    Route::put('grossesses/{grossesse}', [GrossesseController::class, 'update']);
    Route::patch('grossesses/{grossesse}', [GrossesseController::class, 'update']);

    Route::get('bebes', [BebeController::class, 'index']);
    Route::get('bebes/{bebe}', [BebeController::class, 'show']);
    Route::post('bebes', [BebeController::class, 'store']);

    Route::get('vaccinations', [VaccinationController::class, 'index']);
    Route::get('vaccinations/{vaccination}', [VaccinationController::class, 'show']);
    Route::post('vaccinations', [VaccinationController::class, 'store']);
    Route::patch('vaccinations/{vaccination}', [VaccinationController::class, 'update']);

    Route::get('rendez-vous', [RendezVousController::class, 'index']);
    Route::post('rendez-vous', [RendezVousController::class, 'store']);

    Route::post('scans/resolve', [ScanController::class, 'resolve']);

    Route::get('familles/{uuid}', [FamilleController::class, 'show']);
    Route::get('familles/{uuid}/maman', [FamilleController::class, 'maman']);
    Route::get('familles/{uuid}/bebes/{bebeUuid}', [FamilleController::class, 'bebe']);
});
