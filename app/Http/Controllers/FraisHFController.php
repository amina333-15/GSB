<?php

namespace App\Http\Controllers;

use App\Services\FraisService;
use App\Services\FraisHFService;
use Illuminate\Http\Request;
use App\Models\FraisHF;

class FraisHFController extends Controller
{
    public function listFraisHF($id)
    {
        $fraisService = new FraisService();
        $hfService = new FraisHFService();

        $frais = $fraisService->getFrais($id);
        $listeHF = $hfService->getListFraisHF($id);
        $totalHF = $hfService->getTotalHF($id);

        return view('listFraisHF', compact('frais', 'listeHF', 'totalHF'));
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
        $fraisHF = $idHF ? $hfService->getFraisHF($idHF) : new FraisHF();

        $fraisHF->id_frais = $id;
        $fraisHF->date_fraishorsforfait = $request->input('date');
        $fraisHF->lib_fraishorsforfait = $request->input('libelle');
        $fraisHF->montant_fraishorsforfait = $request->input('montant');

        $hfService->saveFraisHF($fraisHF);

        return redirect()->route('listFraisHF', ['id' => $id]);
    }

    public function removeFraisHF($idHF)
    {
        $fraisHF = (new FraisHFService())->getFraisHF($idHF);
        $id = $fraisHF->id_frais;
        (new FraisHFService())->deleteFraisHF($idHF);
        return redirect()->route('listFraisHF', ['id' => $id]);
    }
}
