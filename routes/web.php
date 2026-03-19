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


//Route::get('/listerFraisHF/{id}', [FraisHFController::class, 'listFraisHF']);
Route::get('/listerFraisHF/{id}', [FraisHFController::class, 'listFraisHF'])
    ->name('listFraisHF');

Route::get('/ajouterFraisHF/{id}', [FraisHFController::class, 'addFraisHF'])->name('addFraisHF');
Route::get('/editerFraisHF/{idHF}', [FraisHFController::class, 'editFraisHF'])->name('editFraisHF');
Route::post('/validerFraisHF', [FraisHFController::class, 'validFraisHF'])->name('validFraisHF');
Route::get('/supprimerFraisHF/{idHF}', [FraisHFController::class, 'removeFraisHF'])->name('removeFraisHF');

Route::get('/rechercherVisiteur', [VisiteurController::class, 'searchForm']);
Route::post('/rechercherVisiteur', [VisiteurController::class, 'search']);

Route::get('/visiteur/{id}/affecter-region', [VisiteurController::class, 'formAffectationRegion']);
Route::post('/visiteur/{id}/affecter-region', [VisiteurController::class, 'affecterRegion']);
Route::get('/visiteur/{id}/supprimer-affectation', [VisiteurController::class, 'supprimerAffectation']);

Route::get('/visiteur/{id}/listRegion', [VisiteurController::class, 'listRegion']);

Route::get('/visiteur/{id}/modifier-region/{idRegion}', [VisiteurController::class, 'formModifierRegion']);
Route::post('/visiteur/{id}/modifier-region/{idRegion}', [VisiteurController::class, 'modifierRegion']);

Route::get('/visiteur/{id}/supprimer-region/{idRegion}', [VisiteurController::class, 'supprimerRegion']);

Route::get('/region/{idRegion}/visiteurs', [VisiteurController::class, 'visiteursParRegion']);
Route::get('/visiteursParRegion', [VisiteurController::class, 'choisirRegion']);
Route::get('/choisirRegion', [VisiteurController::class, 'choisirRegion']);

Route::get('/region', function (Illuminate\Http\Request $request) {
    return redirect('/region/'.$request->idRegion.'/visiteurs');
});

Route::get('/top10Laboratoires', [VisiteurController::class, 'top10Laboratoires']);
