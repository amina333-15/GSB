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

    public function addFrais_API($id)
    {
        $service = new FraisService();
        $frais = $service->getFrais($id);


        }

        $frais->save();

        return response()->json([
            'Message'  => 'Insertion réalisée',
            'id_frais' => $frais->id_frais,
        ], 201);
    }


    public function updateFrais_API(Request $request, $id)
    {
        $service = new FraisService();
        $frais = $service->getFrais($id);

        if (!$frais) {
            return response()->json([
                'Message' => 'Frais introuvable'
            ], 404);
        }

        // Exemple de mise à jour des champs
        $frais->lib_frais = $request->input('lib_frais', $frais->lib_frais);
        $frais->montant   = $request->input('montant', $frais->montant);
        $frais->id_etat   = $request->input('id_etat', $frais->id_etat);

        $service->saveFrais($frais);

        return response()->json([
            'Message'  => 'Modification réalisée',
            'id_frais' => $frais->id_frais,
        ], 200);
    }

    public function removeFrais_API($id)
    {
        $service = new FraisService();
        $frais = $service->getFrais($id);

        if (!$frais) {
            return response()->json([
                'Message' => 'Frais introuvable'
            ], 404);
        }

        $service->deleteFrais($id);

        return response()->json([
            'Message' => 'Suppression réalisée',
            'id_frais' => $id,
        ], 200);
    }

    public function listFrais_API($idVisiteur)
    {
        $service = new FraisService();
        $liste = $service->getListFrais($idVisiteur);

        return response()->json([
            'Frais'   => $liste,
        ], 200);
    }
}
