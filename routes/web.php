<?php

use App\Http\Controllers\AccueilController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CarteController;
use App\Http\Controllers\CotisationController;
use App\Http\Controllers\MembreController;
use App\Http\Controllers\ParametreController;
use App\Http\Controllers\VerificationController;
use Illuminate\Support\Facades\Route;

// Page publique ouverte par le QR code de la carte.
Route::get('/verification/{token}', VerificationController::class)->name('verification');

Route::middleware('guest')->group(function () {
    Route::get('/connexion', [AuthController::class, 'formulaire'])->name('connexion');
    Route::post('/connexion', [AuthController::class, 'connexion'])->middleware('throttle:10,1');
});

Route::middleware('auth')->group(function () {
    Route::get('/', AccueilController::class)->name('accueil');
    Route::post('/deconnexion', [AuthController::class, 'deconnexion'])->name('deconnexion');

    Route::resource('membres', MembreController::class);
    Route::post('/membres/{membre}/cotisations', [CotisationController::class, 'store'])->name('cotisations.store');

    Route::get('/cartes', [CarteController::class, 'index'])->name('cartes.index');
    Route::post('/cartes/imprimer', [CarteController::class, 'imprimer'])->name('cartes.imprimer');
    Route::post('/cartes/marquer', [CarteController::class, 'marquer'])->name('cartes.marquer');

    Route::get('/parametres', [ParametreController::class, 'index'])->name('parametres.index');
    Route::post('/parametres', [ParametreController::class, 'enregistrer'])->name('parametres.enregistrer');
    Route::post('/parametres/categories', [ParametreController::class, 'categorieStore'])->name('categories.store');
    Route::put('/parametres/categories/{categorie}', [ParametreController::class, 'categorieUpdate'])->name('categories.update');
});
