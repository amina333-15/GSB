<?php

namespace App\Http\Controllers;

use App\Services\FraisService;
use Exception;
use App\Services\FraisHFService;
use Illuminate\Http\Request;
use App\Models\FraisHF;
use App\Models\Etat;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;


class FraisHFController extends Controller
{
    public function listFraisHF($id)
    {
        try {
            $fraisService = new FraisService();
            $hfService = new FraisHFService();

            // Récupération des données
            $frais = $fraisService->getFrais($id);
            $listeHF = $hfService->getListFraisHF($id);
            $totalHF = $hfService->getTotalHF($id);

            // Passage à la vue
            return view('listFraisHF', compact('frais', 'listeHF', 'totalHF'));
        } catch (Exception $exception) {
            return view('error', compact('exception'));
        }
    }



    public function addFraisHF($id)
    {
        $fraisHF = new FraisHF();
        $fraisHF->id_frais = $id;

        return view('formFraisHF', compact('fraisHF'));
    }

    public function editFraisHF($idHF)
    {
        $fraisHF = (new FraisHFService())->getFraisHF($idHF);

        return view('formFraisHF', compact('fraisHF'));
    }

    public function validFraisHF(Request $request)
    {
        $id = $request->input('id_frais');
        $idHF = $request->input('id_fraishorsforfait');

        $hfService = new FraisHFService();
        $fraisHF = $idHF ? (new FraisHFService())->getFraisHF($idHF) : new FraisHF();

        $fraisHF->id_frais = $id;
        $fraisHF->date_fraishorsforfait = $request->input('date');
        $fraisHF->lib_fraishorsforfait = $request->input('libelle');
        $fraisHF->montant_fraishorsforfait = $request->input('montant');

        $hfService->saveFraisHF($fraisHF);

        return redirect()->route('listFraisHF', ['id' => $id]);
    }

    public function removeFraisHF($idHF)
    {
//        $fraisHF = (new FraisHFService())->getFraisHF($idHF);
//        $id = $fraisHF->id_frais;
//
//        (new FraisHFService())->deleteFraisHF($idHF);
//
//        return redirect()->route('listFraisHF', ['id' => $id]);
    }

}
