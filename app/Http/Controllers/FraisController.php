<?php

namespace App\Http\Controllers;
use App\Services\FraisService;
use Illuminate\Http\Request;
use App\Models\Frais;

class FraisController extends Controller
{
    public function listFrais(){
        $service = new FraisService();
        $id_visiteur = session('id_visiteur');
        $fiches = $service->getListFrais($id_visiteur);
        return view('listFrais', compact('fiches'));
    }

    public function addFrais()
    {
        $frais = new Frais();
        $frais->anneemois=date("Y-m");

        return view('formFrais', compact('frais'));
    }

    public function validFrais(Request $request)
    {
        $id_frais = $request->input('id');
        $service = new FraisService();

        if ($id_frais) {
            $frais = $service->getFrais($id_frais);
            $frais->id_visiteur = session('id_visiteur');
        } else {
            $frais = new Frais();
            $frais->id_visiteur = session('id_visiteur');
        }

        $frais->anneemois = $request->input('mois');
        $frais->nbjustificatifs = $request->input('nbjustif');
        $frais->montantvalide = $request->input('valide');
        $frais->id_etat = $request->input('etat');
        $frais->datemodification = date('Y-m-d');

        $service = new FraisService();
        $service->saveFrais($frais);
        return redirect(url('/listerFrais'));
    }


    public function editFrais($id)
    {
            $service = new FraisService();
            $frais = $service->getFrais($id);
            return view('formFrais', compact('frais'));
    }
}

