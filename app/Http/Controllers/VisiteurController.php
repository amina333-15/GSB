<?php

namespace App\Http\Controllers;

use App\Models\Visiteur;
use App\Services\VisiteurService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class VisiteurController extends Controller
{
    public function login()
    {
        try{
            return view('formLogin');
        }catch (Exception $exception){
            return view('error', compact('exception'));
        }
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
        try{
            $login = $request->input("login");
            $pwd = $request->input("pwd");

            $service = new VisiteurService();
            if ($service->signIn($login, $pwd)) {
                return redirect(url('/'));
            } else {
                $erreur = "Identifiant ou mot de passe incorrect";
                return view('/formLogin', compact('erreur'));
            }
        }catch (Exception $exception){
            return view('error', compact('exception'));
        }
    }

    public function logout()
    {
        try {
            $service = new VisiteurService();
            $service->signOut();
            return redirect(url('/'));
        } catch (Exception $exception) {
            return view('error', compact('exception'));
        }
    }


    public function initPasswordAPI(Request $request)
    {
        try{
            $request->validate(['pwd_visiteur'=>'required|min:3']);
            $hash = bcrypt($request->input('pwd_visiteur'));
            Visiteur::query()->update(['pwd_visiteur'=>$hash]);
            return response()->json(['status'=>'mot de passe réinitialisés']);
        }catch (\Exception $exception){
            return response()->json(['error'=>$exception->getMessage()],500);
        }
    }

    public function authAPI(Request $request)
    {
        try {
            $request->validate([
                'login'=>'required',
                'pwd'=>'required'
            ]);
            $login = $request->input("login");
            $pwd = $request->input("pwd");
            $identifiants = ["login_visiteur"=>$login, "password" =>$pwd];
            if (!Auth::attempt($identifiants)) {
                return response()->json(['error'=>'Identifiant incorrect'],401);
            }
            //creation token et retour informations
            $visiteur = $request->user();
            $token = $visiteur->CreateToken('authToken')->plainTextToken;
            return response()->json([
               'token'=>$token,
               'token_type'=>'Bearer',
               'visiteur'=>[
                   'id_visiteur'=>$visiteur->id_visiteur,
                   'nom_visiteur'=>$visiteur->nom_visiteur,
                   'prenom_visiteur'=>$visiteur->prenom_visiteur,
                   'type_visiteur'=>$visiteur->type_visiteur,
               ]
            ]);
       } catch (Exception $exception) {
            return response()->json(['error'=>$exception->getMessage()],500);
        }
    }

    public function logoutAPI(Request $request)
    {
        try{
            $request->user()->tokens()->delete();
            return response()->json(['status' => 'utilisateur déconnecté']);
        }catch(Exception $exception){
            return response()->json(['error'=>$exception->getMessage()],500);
        }

    }
public function unauthorizedAPI(Request $request)
{
        return response()->json(['error'=>'accès non autorisé'],401);
}
}

