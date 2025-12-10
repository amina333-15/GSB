<?php

namespace App\Http\Controllers;

use Exception;
use App\Services\FraisService;
use Illuminate\Http\Request;
use App\Models\Frais;
use App\Models\Etat;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;


class FraisController extends Controller
{
    public function listFrais()
    {
        try {
            $service = new FraisService();
            $id_visiteur = session('id_visiteur');
            $fiches = $service->getListFrais($id_visiteur);
            return view('listFrais', compact('fiches'));
        } catch (Exception $exception) {
            return view('error', compact('exception'));
        }
    }

    public function addFrais()
    {
        try {
            $frais = new Frais();
            $frais->anneemois = date("Y-m");
            $frais->datemodification = Carbon::now(); // Ajout de la date actuelle

            $etat = new Etat();
            $etat->id_etat = 1;
            $etat->lib_etat = "Création en cours";
            $etats = [$etat];

            return view('formFrais', compact('frais', 'etats'));
        } catch (Exception $exception) {
            return view('error', compact('exception'));
        }
    }


    public function validFrais(Request $request)
    {
        try {
            $id_frais = $request->input('id');
            $service = new FraisService();

            if ($id_frais) {
                $frais = $service->getFrais($id_frais);
            } else {
                $frais = new Frais();
            }

            $frais->id_visiteur = session('id_visiteur');
            $frais->id_etat = $request->input('id_etat');
            $frais->anneemois = $request->input('mois');
            $frais->titre = $request->input('titre');
            $frais->datemodification = $request->input('datemodification');
            $frais->nbjustificatifs = $request->input('nbjustif');
            $frais->montantvalide = $request->input('valide');

            $service->saveFrais($frais);
            return redirect(url('/listerFrais'));
        } catch (Exception $exception) {
            return view('error', compact('exception'));
        }
    }

    public function editFrais($id)
    {
        try {
            $service = new FraisService();
            $frais = $service->getFrais($id);

            // Correction de l'erreur format() sur string
            $frais->datemodification = Carbon::parse($frais->datemodification);

            // Initialisation de $etats
            $etats = Etat::all();
            $erreur = Session::get('erreur');
            Session::remove( 'erreur');
//            session()->forget('erreur');

            return view('formFrais', compact('frais', 'etats', 'erreur'));
        } catch (Exception $exception) {
            return view('error', compact('exception'));
        }
    }

    public function removeFrais($id)
    {
        try {
            $service = new FraisService();
            $service->deleteFrais($id);
            return redirect()->route('listFrais');
        } catch (Exception $exception) {
            if ($exception->getCode() == 23000) {
                Session::put('erreur', "Impossible de supprimer une fiche avec des frais saisis.");
                return redirect(url('/editerFrais/' . $id));
            } else {
                return view('error', compact('exception'));
            }
        }
    }
    /* =========================
           API : GET /api/frais/{idFrais}
           ========================= */
    public function getFrais_API($idFrais)
    {
        try {
            $service = new FraisService();
            $frais = $service->getFrais($idFrais);

            if (!$frais) {
                return response()->json(['error' => 'Frais introuvable'], 404);
            }

            $visiteur = Auth::user();
            if ($frais->id_visiteur != $visiteur->id_visiteur) {
                return response()->json(['error' => 'accès non autorisé'], 401);
            }

            return response()->json($frais, 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /* =========================
       API : POST /api/frais/ajout
       ========================= */
    public function addFrais_API(Request $request)
    {
        try {
            $visiteur = Auth::user();
            if ($request->id_visiteur != $visiteur->id_visiteur) {
                return response()->json(['error' => 'accès non autorisé'], 401);
            }

            $frais = new Frais();
            $frais->id_visiteur = $visiteur->id_visiteur;
            $frais->anneemois = $request->input('anneemois');
            $frais->nbjustificatifs = $request->input('nbjustificatifs');
            $frais->datemodification = Carbon::now();
            $frais->id_etat = 1; // état par défaut

            $service = new FraisService();
            $service->saveFrais($frais);

            return response()->json([
                'message' => 'Insertion réalisée',
                'id_frais' => $frais->id_frais,
            ], 201);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /* =========================
       API : POST /api/frais/modif
       ========================= */
    public function updateFrais_API(Request $request, $id)
    {
        try {
            $service = new FraisService();
            $frais = $service->getFrais($id);

            if (!$frais) {
                return response()->json(['error' => 'Frais introuvable'], 404);
            }

            $visiteur = Auth::user();
            if ($frais->id_visiteur != $visiteur->id_visiteur) {
                return response()->json(['error' => 'accès non autorisé'], 401);
            }

            $frais->anneemois = $request->input('anneemois', $frais->anneemois);
            $frais->nbjustificatifs = $request->input('nbjustificatifs', $frais->nbjustificatifs);
            $frais->montantvalide = $request->input('montantvalide', $frais->montantvalide);
            $frais->id_etat = $request->input('id_etat', $frais->id_etat);

            $service->saveFrais($frais);

            return response()->json([
                'message' => 'Modification réalisée',
                'id_frais' => $frais->id_frais,
            ], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /* =========================
       API : DELETE /api/frais/suppr
       ========================= */
    public function removeFrais_API($id)
    {
        try {
            $service = new FraisService();
            $frais = $service->getFrais($id);

            if (!$frais) {
                return response()->json(['error' => 'Frais introuvable'], 404);
            }

            $visiteur = Auth::user();
            if ($frais->id_visiteur != $visiteur->id_visiteur) {
                return response()->json(['error' => 'accès non autorisé'], 401);
            }

            $service->deleteFrais($id);

            return response()->json([
                'message' => 'Suppression réalisée',
                'id_frais' => $id,
            ], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /* =========================
       API : GET /api/frais/liste/{idVisiteur}
       ========================= */
    public function listFrais_API($idVisiteur)
    {
        try {
            $visiteur = Auth::user();
            if ($idVisiteur != $visiteur->id_visiteur) {
                return response()->json(['error' => 'accès non autorisé'], 401);
            }

            $service = new FraisService();
            $liste = $service->getListFrais($idVisiteur);

            return response()->json($liste, 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
