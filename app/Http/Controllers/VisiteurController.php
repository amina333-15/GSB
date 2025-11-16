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

    public function auth(Request $request)
    {
        $login = $request->input("login");
        $pwd = $request->input("pwd");

        $service = new VisiteurService();
        if ($service->signIn($login, $pwd)) {
            return redirect(url('/'));
        } else {
            $erreur = "Identifiant ou mot de passe incorrect";
            return view('formLogin', compact('erreur'));
        }
    }

    public function logout()
    {
        $service = new VisiteurService();
        $service->signOut();
        return redirect(url('/'));
    }
}
