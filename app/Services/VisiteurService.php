<?php

namespace App\Services;

use App\Exceptions\UserException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Session;
use App\Models\Visiteur;


class VisiteurService
{
    public function signIn($login, $pwd)
    {
        try{
        $visiteur = Visiteur::query()->where('login_visiteur', '=', $login)->first();
        if ($visiteur && password_verify($pwd,$visiteur->pwd_visiteur)) {
            Session::put('id_visiteur', $visiteur->id_visiteur);
            Session::put('visiteur', "$visiteur->prenom_visiteur $visiteur->nom_visiteur");
            return true;
        }
    } catch(QueryException $exception)
        {
            $userMessage="Impossible d'accéder à la base de données.";
            throw new UserException($userMessage, $exception->getMessage(), $exception->getCode());
        }
        return false;
    }

    public function signOut()
    {
        try
        {
            Session::remove('id_visiteur');
        }
        catch(QueryException $exception)
        {
            $userMessage="Impossible d'accéder à la base de données.";
            throw new UserException($userMessage, $exception->getMessage(), $exception->getCode());
        }
    }

}
