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
        $service = new FraisService();
        $id_visiteur = session('id_visiteur');
        $fiches = $service->getListFrais($id_visiteur);
        return view('listFrais', compact('fiches'));
    }

    public function addFrais()
    {
        $frais = new Frais();
        $frais->anneemois = date("Y-m");
        $frais->datemodification = Carbon::now();

        $etat = new Etat();
        $etat->id_etat = 1;
        $etat->lib_etat = "Création en cours";
        $etats = [$etat];

        return view('formFrais', compact('frais', 'etats'));
    }

    public function validFrais(Request $request)
    {
        $id_frais = $request->input('id');
        $service = new FraisService();

        $frais = $id_frais ? $service->getFrais($id_frais) : new Frais();

        $frais->id_visiteur = session('id_visiteur');
        $frais->id_etat = $request->input('id_etat');
        $frais->anneemois = $request->input('mois');
        $frais->titre = $request->input('titre');
        $frais->datemodification = $request->input('datemodification');
        $frais->nbjustificatifs = $request->input('nbjustif');
        $frais->montantvalide = $request->input('valide');

        $service->saveFrais($frais);
        return redirect()->route('listFrais');
    }

    public function editFrais($id)
    {
        $service = new FraisService();
        $frais = $service->getFrais($id);
        $frais->datemodification = Carbon::parse($frais->datemodification);

        $etats = Etat::all();
        $erreur = Session::get('erreur');
        Session::forget('erreur');

        return view('formFrais', compact('frais', 'etats', 'erreur'));
    }

    public function removeFrais($id)
    {
        $service = new FraisService();
        $service->deleteFrais($id);
        return redirect()->route('listFrais');
    }
}
