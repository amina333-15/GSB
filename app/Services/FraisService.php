<?php

namespace App\Services;

use App\Exceptions\UserException;
use App\Models\Etat;
use App\Models\Frais;
use Illuminate\Database\QueryException;

class FraisService
{
    /**
     * Liste des fiches de frais du visiteur avec libellé d'état.
     */
    public function getListFrais($id_visiteur)
    {
        try {
            return Frais::query()
                ->select('frais.*', 'etat.lib_etat')
                ->join('etat', 'etat.id_etat', '=', 'frais.id_etat')
                ->where('id_visiteur', '=', $id_visiteur)
                ->orderBy('datemodification', 'desc')
                ->get();
        } catch (QueryException $exception) {
            $userMessage = "Impossible d'accéder à la base de données.";
            throw new UserException($userMessage, $exception->getMessage(), $exception->getCode());
        }
    }

    /**
     * Récupère une fiche de frais par son identifiant.
     */
    public function getFrais($id)
    {
        try {
            $frais = Frais::query()->find($id);
            return $frais;
        } catch (QueryException $exception) {
            $userMessage = "Impossible d'accéder à la base de données.";
            throw new UserException($userMessage, $exception->getMessage(), $exception->getCode());
        }
    }

    /**
     * Enregistre une fiche (création ou mise à jour).
     */
    public function saveFrais(Frais $frais)
    {
        try {
            $frais->save();
        } catch (QueryException $exception) {
            $userMessage = "Impossible d'enregistrer la fiche de frais.";
            throw new UserException($userMessage, $exception->getMessage(), $exception->getCode());
        }
    }

    /**
     * Retourne la liste des états (pour le select du formulaire).
     */
    public function getListEtats()
    {
        try {
            return Etat::query()->orderBy('id_etat')->get();
        } catch (QueryException $exception) {
            $userMessage = "Impossible d'accéder à la liste des états.";
            throw new UserException($userMessage, $exception->getMessage(), $exception->getCode());
        }
    }

    /**
     * Supprime une fiche de frais.
     * Lève un message utilisateur si des frais sont liés (contrainte d'intégrité).
     */
    public function deleteFrais($id)
    {
        try {
            $frais = Frais::query()->findOrFail($id);
            $frais->delete();
        } catch (QueryException $exception) {
            if ($exception->getCode() == 23000) {
                // Violation contrainte d'intégrité (clé étrangère)
                $userMessage = "Impossible de supprimer une fiche avec des frais saisis.";
            } else {
                $userMessage = "Erreur de suppression dans la base de données.";
            }
            throw new UserException($userMessage, $exception->getMessage(), $exception->getCode());
        }
    }
}
