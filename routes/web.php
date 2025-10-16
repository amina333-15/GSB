<?php

use App\Http\Controllers\FraisHFController;
use App\Http\Controllers\VisiteurController;
use App\Http\Controllers\FraisController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});
Route::get('/connecter', [VisiteurController::class, 'login']);
Route::post('/authentifier', [VisiteurController::class, 'auth']);
Route::get('/deconnecter', [VisiteurController::class, 'logout']);


Route::get('/listerFrais', [FraisController::class, 'listFrais'])->name('listFrais');
Route::get('/ajouterFrais', [FraisController::class, 'addFrais']);
Route::get('/editerFrais/{id}', [FraisController::class, 'editFrais']);
Route::post('/validerFrais', [FraisController::class, 'validFrais']);
Route::get('/supprimerFrais/{id}', [FraisController::class, 'removeFrais'])->name('supprimerFrais');


Route::get('/listerFraisHF/{id}', [FraisHFController::class, 'listFraisHF']);
Route::get('/ajouterFraisHF/{id}', [FraisHFController::class, 'addFraisHF'])->name('addFraisHF');
Route::get('/editerFraisHF/{idHF}', [FraisHFController::class, 'editFraisHF'])->name('editFraisHF');
Route::post('/validerFraisHF', [FraisHFController::class, 'validFraisHF'])->name('validFraisHF');
Route::get('/supprimerFraisHF/{idHF}', [FraisHFController::class, 'removeFraisHF'])->name('removeFraisHF');
