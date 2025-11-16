<?php

namespace App\Services;

use Illuminate\Support\Facades\Session;
use App\Models\Visiteur;

class VisiteurService
{
    public function signIn($login, $pwd)
    {
        $visiteur = Visiteur::where('login_visiteur', $login)->first();
        if ($visiteur && $visiteur->pwd_visiteur === $pwd) {
            Session::put('id_visiteur', $visiteur->id_visiteur);
            Session::put('visiteur', "$visiteur->prenom_visiteur $visiteur->nom_visiteur");
            return true;
        }
        return false;
    }

    public function signOut()
    {
        Session::forget('id_visiteur');
        Session::forget('visiteur');
    }
}
