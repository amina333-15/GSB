<?php

namespace App\Http\Controllers;

use App\Services\VisiteurService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class VisiteurController extends Controller
{
    public function login()
    {
        return view('formLogin');
    }

//
//    public function auth(Request $request)
//    {
//        $login = $request->input("login");
//        $pwd = $request->input("pwd");
//
//        $service = new VisiteurService();
//        $visiteur = $service->signIn($login, $pwd);
//
//        if ($visiteur && $visiteur->pwd_visiteur === $pwd) {
//            Session::put('id_visiteur', $visiteur->id_visiteur);
//            Session::put('visiteur', "{$visiteur->prenom_visiteur} {$visiteur->nom_visiteur}");
//            return redirect(url('/'));
//        } else {
//            $erreur = "Identifiant ou mot de passe incorrect";
//            return view('formLogin', compact('erreur'));
//        }
//    }


    public function auth(Request $request)
    {
        $login = $request->input("login");
        $pwd = $request->input("pwd");

        $service = new VisiteurService();
        if ($service->signIn($login, $pwd)) {
            return redirect(url('/'));
        } else {
            $erreur = "Identifiant ou mot de passe incorrect";
            return view('/formLogin', compact('erreur'));
        }
    }

    public function logout()
    {
        $service = new VisiteurService();
        $service->signOut();
        return redirect(url('/'));
    }

}
