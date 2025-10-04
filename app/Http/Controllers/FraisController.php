<?php

namespace App\Http\Controllers;

use Exception;
use App\Services\FraisService;
use Illuminate\Http\Request;
use App\Models\Frais;
use App\Models\Etat;
use Illuminate\Support\Facades\Session;

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
            
            $etats = [new Etat()];
            $etats[0]->lib_etat = "Création en cours";
            
            return view('formFrais', compact('frais', 'etats'));
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
                $frais->id_etat = $request->input('etat');
            } else {
                $frais = new Frais();
                $frais->id_visiteur = session('id_visiteur');
                $frais->id_etat = 2;
            }
            $frais->anneemois = $request->input('mois');

            $frais->titre = $request->input('titre');
            $frais->datemodification = date('Y-m-d');
            
            $frais->nbjustificatifs = $request->input('nbjustif');
            $frais->montantvalide = $request->input('valide');
            $frais->id_etat = $request->input('etat');

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

            $erreur = session()->get('erreur');    // Récupérer le message d'erreur s'il existe
            session()->forget('erreur');    // Supprimer la variable de session pour éviter qu'elle reste

            return view('formFrais', compact('frais', 'etats'));
        } catch (Exception $exception) {
            return view('error', compact('exception'));
        }
    }

    public function removeFrais($id)
    {
        try{
            $service = new FraisService();
            $service->deleteFrais($id);
            return redirect()->route('listeFrais');
        } catch (Exception $exception) {
            if($exception->getCode() == 23000){
                Session::put('erreur', $exception->getUserMessage());
                return redirect(url('/editerFrais/'.$id));
            }else{
                return view('error', compact('exception'));
            }
        }
    }
}
