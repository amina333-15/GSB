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

            // ✅ Correction de l'erreur format() sur string
            $frais->datemodification = Carbon::parse($frais->datemodification);

            // ✅ Initialisation de $etats
            $etats = Etat::all();

            session()->forget('erreur');

            return view('formFrais', compact('frais', 'etats'));
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
                Session::put('erreur', $exception->getMessage());
                return redirect(url('/editerFrais/' . $id));
            } else {
                return view('error', compact('exception'));
            }
        }
    }
}
