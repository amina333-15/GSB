<?php

namespace App\Services;

use App\Models\Frais;


class FraisService
{
    public function getListFrais($id_visiteur){

        try
        {
            $liste = Frais::query()->where('id_visiteur', '=', $id_visiteur)->get();
        }
        catch(QueryException $exception)
        {
            $userMessage="Impossible d'accéder à la base de données.";
            throw new UserException($userMessage, $exception->getMessage(), $exception->getCode());
        }

        return $liste;
    }

    public function getFrais($id){
        try
        {
        $frais = Frais::query()->find($id);
        }
        catch(QueryException $exception)
        {
            $userMessage="Impossible d'accéder à la base de données.";
            throw new UserException($userMessage, $exception->getMessage(), $exception->getCode());
        }
        return $frais;
    }

    public function saveFrais(Frais $frais){
        try
        {
        $frais->save();
        }
        catch(QueryException $exception)
        {
            $userMessage="Impossible d'accéder à la base de données.";
            throw new UserException($userMessage, $exception->getMessage(), $exception->getCode());
        }
    }

}
