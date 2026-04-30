<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VisiteurController;
use App\Http\Controllers\FraisController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/visiteur/initpwd', [VisiteurController::class, "initPasswordAPI"]);

Route::post('/visiteur/auth', [VisiteurController::class, "authAPI"]);
Route::get('/visiteur/logout', [VisiteurController::class, "logoutAPI"])->middleware('auth:sanctum');

Route::get('/visiteur/unauthorized', [VisiteurController::class, "unauthorizedAPI"])->name('login');


Route::get('/frais/{idFrais}', [FraisController::class, "getFrais_API"])->middleware('auth:sanctum');
Route::post('/frais/ajout', [FraisController::class, "addFrais_API"])->middleware('auth:sanctum');
Route::post('/frais/modif', [FraisController::class, "updateFrais_API"])->middleware('auth:sanctum');
Route::delete('/frais/suppr', [FraisController::class, "removeFrais_API"])->middleware('auth:sanctum');

Route::get('/frais/liste/{idVisiteur}', [FraisController::class, "listFrais_API"])->middleware('auth:sanctum');


//-------------------------------------------------------------------------------------------
// Mission 4 : API visiteurs
Route::get('/regions', [VisiteurController::class, 'apiRegions']);
Route::post('/rechercherVisiteur', [VisiteurController::class, 'apiSearch']);
Route::get('/region/{id}/visiteurs', [VisiteurController::class, 'apiVisiteursParRegion']);
Route::get('/top10laboratoires', [VisiteurController::class, 'apitop10Laboratoires']);

Route::post('/visiteur/{id}/affecter', [VisiteurController::class, 'apiAffecterRegion']);
Route::post('/visiteur/{id}/affectation/modifier', [VisiteurController::class, 'apiModifierAffectation']);
Route::delete('/visiteur/{id}/affectation/supprimer', [VisiteurController::class, 'apiSupprimerAffectation']);

Route::get('/visiteur/{id}/affectations', [VisiteurController::class, 'apiAffectationsVisiteur']);

//Route pour api gsb-react
Route::get('/regions', [VisiteurController::class, 'apiRegions']);
Route::post('/rechercherVisiteur', [VisiteurController::class, 'apiSearch']);
Route::get('/region/{idRegion}/visiteurs', [VisiteurController::class, 'apiVisiteursParRegion']);
