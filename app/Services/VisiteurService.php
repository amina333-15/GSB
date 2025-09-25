<?php

namespace App\Services;

use Illuminate\Support\Facades\Session;
use App\Models\Visiteur;

class VisiteurService
{
    public function signIn($login, $pwd)
    {
        $visiteur = Visiteur::query()->where('login_visiteur', '=', $login)->first();
        if ($visiteur && $visiteur->pwd_visiteur === $pwd) {
            Session::put('id_visiteur', $visiteur->id_visiteur);
            return true;
        }
        return false;
    }

    public function signOut()
    {
        Session::remove('id_visiteur');
    }

}
