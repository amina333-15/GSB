<?php

namespace App\Services;
use App\Exceptions\UserException;
use App\Models\Etat;
use App\Models\Frais;
use Illuminate\Database\QueryException;


class FraisService
{
    public function getListFrais($id_visiteur)
    {

        try {
            $liste = Frais::query()
                ->select('frais.*', 'etat.lib_etat')
                ->join('etat', 'etat.id_etat', '=', 'frais.id_etat')
                ->where('id_visiteur', '=', $id_visiteur)
                ->get();
        } catch (QueryException $exception) {
            $userMessage = "Impossible d'accéder à la base de données.";
            throw new UserException($userMessage, $exception->getMessage(), $exception->getCode());
        }

        return $liste;
    }

    public function getFrais($id)
    {
        try {
            $frais = Frais::query()->find($id);
        } catch (QueryException $exception) {
            $userMessage = "Impossible d'accéder à la base de données.";
            throw new UserException($userMessage, $exception->getMessage(), $exception->getCode());
        }
        return $frais;
    }

    public function saveFrais(Frais $frais)
    {
        try {
            $frais->save();
        } catch (QueryException $exception) {
            $userMessage = "Impossible d'accéder à la base de données.";
            throw new UserException($userMessage, $exception->getMessage(), $exception->getCode());
        }
    }

    public function getListEtats()
    {
        try {
            Etat::query()->get();
        } catch (QueryException $exception) {
            $userMessage = "Impossible d'accéder à la base de données.";
            throw new UserException($userMessage, $exception->getMessage(), $exception->getCode());
        }
    }

    public function deleteFrais($id)
    {
        try {
            $frais = Frais::query()->find($id);
            $frais->delete();
        } catch (QueryException $exception) {
            if ($exception->getCode() == 23000) {
                $userMessage = "Impossible de supprimer une fiche avec des frais saisis.";
            } else {
                $userMessage = "Erreur de suppression dans le base de données.";
            }
            throw new UserException($userMessage, $exception->getMessage(), $exception->getCode());
        }
    }

}
