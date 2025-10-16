<?php

namespace App\Services;
use App\Exceptions\UserException;
use App\Models\Etat;
use App\Models\FraisHF;
use Illuminate\Database\QueryException;


class FraisHFService
{
    public function getFrais($id)
    {

    }
    public function getListFraisHF($id)
    {
        try {
            $liste = FraisHF::query()
                ->where('fraishorsforfait.id_frais', $id)
                ->orderBy('fraishorsforfait.date_fraishorsforfait', 'desc')
                ->join('frais', 'frais.id_frais', '=', 'fraishorsforfait.id_frais')
                ->join('etat', 'etat.id_etat', '=', 'frais.id_etat')
                ->select('fraishorsforfait.*', 'etat.lib_etat')
                ->get();

            return $liste;
        } catch (QueryException $exception) {
            throw new \Exception("Erreur lors de la récupération des frais hors forfait : " . $exception->getMessage());
        }
    }

    public function getFraisHF($idHF)
    {
        return FraisHF::find($idHF);
    }

    public function saveFraisHF(FraisHF $fraisHF)
    {
        $fraisHF->save();
    }

    public function deleteFraisHF($idHF)
    {
        $fraisHF = FraisHF::find($idHF);
        if ($fraisHF) {
            $fraisHF->delete();
        }
    }

    public function getTotalHF($id)
    {
        return FraisHF::where('id_frais', $id)->sum('montant_fraishorsforfait');
    }



}
