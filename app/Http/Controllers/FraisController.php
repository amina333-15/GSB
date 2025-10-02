<?php

namespace App\Http\Controllers;

use Exception;
use App\Services\FraisService;
use Illuminate\Http\Request;
use App\Models\Frais;

class FraisController extends Controller
{
    public function listFrais(){
        try{
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
        try{
            $frais = new Frais();
            $frais->anneemois=date("Y-m");
            return view('formFrais', compact('frais'));
        } catch (Exception $exception) {
            return view('error', compact('exception'));
        }
    }

    public function validFrais(Request $request)
    {
        try{
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
        } catch (Exception $exception) {
            return view('error', compact('exception'));
        }
    }


    public function editFrais($id)
    {
        try{
            $service = new FraisService();
            $frais = $service->getFrais($id);
            return view('formFrais', compact('frais'));
        } catch (Exception $exception) {
            return view('error', compact('exception'));
        }
    }
}
