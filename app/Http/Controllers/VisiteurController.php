<?php

namespace App\Http\Controllers;

use App\Models\Frais;
use App\Models\Visiteur;
use App\Services\FraisService;
use App\Services\VisiteurService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class VisiteurController extends Controller
{
    public function login()
    {
        try {
            return view('formLogin');
        } catch (Exception $exception) {
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
        try {
            $login = $request->input("login");
            $pwd = $request->input("pwd");

            $service = new VisiteurService();
            if ($service->signIn($login, $pwd)) {
                return redirect(url('/'));
            } else {
                $erreur = "Identifiant ou mot de passe incorrect";
                return view('/formLogin', compact('erreur'));
            }
        } catch (Exception $exception) {
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
        try {
            $request->validate(['pwd_visiteur' => 'required|min:3']);
            $hash = bcrypt($request->input('pwd_visiteur'));
            Visiteur::query()->update(['pwd_visiteur' => $hash]);
            return response()->json(['status' => 'mot de passe réinitialisés']);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }

    public function authAPI(Request $request)
    {
        try {
            $request->validate([
                'login' => 'required',
                'pwd' => 'required'
            ]);
            $login = $request->input("login");
            $pwd = $request->input("pwd");
            $identifiants = ["login_visiteur" => $login, "password" => $pwd];
            if (!Auth::attempt($identifiants)) {
                return response()->json(['error' => 'Identifiant incorrect'], 401);
            }
            //creation token et retour informations
            $visiteur = $request->user();
            $token = $visiteur->CreateToken('authToken')->plainTextToken;
            return response()->json([
                'token' => $token,
                'token_type' => 'Bearer',
                'visiteur' => [
                    'id_visiteur' => $visiteur->id_visiteur,
                    'nom_visiteur' => $visiteur->nom_visiteur,
                    'prenom_visiteur' => $visiteur->prenom_visiteur,
                    'type_visiteur' => $visiteur->type_visiteur,
                ]
            ]);
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }

    public function logoutAPI(Request $request)
    {
        try {
            $request->user()->tokens()->delete();
            return response()->json(['status' => 'utilisateur déconnecté']);
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }

    }

    public function unauthorizedAPI(Request $request)
    {
        return response()->json(['error' => 'accès non autorisé'], 401);
    }

    public function getFrais_API($id)
    {

        $frais = Frais::query()->find($id);
        if ($frais) {
            return response()->json($frais);
        }

        return response()->json([
            'message' => 'Frais non trouvé'
        ], 404);
    }


    public function searchForm()
    {
        return view('recherche');
    }

    public function search(Request $request)
    {
        $term = $request->input('recherche');

        // Sous-requête : on récupère les visiteurs qui matchent le terme
        $matchingIds = Visiteur::query()
            ->leftJoin('laboratoire', 'visiteur.id_laboratoire', '=', 'laboratoire.id_laboratoire')
            ->leftJoin('travailler', 'visiteur.id_visiteur', '=', 'travailler.id_visiteur')
            ->leftJoin('region', 'travailler.id_region', '=', 'region.id_region')
            ->leftJoin('secteur', 'region.id_secteur', '=', 'secteur.id_secteur')
            ->where('visiteur.nom_visiteur', 'like', "%$term%")
            ->orWhere('laboratoire.nom_laboratoire', 'like', "%$term%")
            ->orWhere('secteur.lib_secteur', 'like', "%$term%")
            ->pluck('visiteur.id_visiteur'); // liste des IDs trouvés

        // Requête finale : on récupère les infos propres
        $visiteurs = Visiteur::query()
            ->leftJoin('laboratoire', 'visiteur.id_laboratoire', '=', 'laboratoire.id_laboratoire')
            ->leftJoin('travailler', 'visiteur.id_visiteur', '=', 'travailler.id_visiteur')
            ->leftJoin('region', 'travailler.id_region', '=', 'region.id_region')
            ->leftJoin('secteur', 'region.id_secteur', '=', 'secteur.id_secteur')
            ->whereIn('visiteur.id_visiteur', $matchingIds)
            ->select(
                'visiteur.nom_visiteur',
                'visiteur.prenom_visiteur',
                'laboratoire.nom_laboratoire',
                'secteur.lib_secteur',
                'region.nom_region'
            )
            ->distinct()
            ->get();

        if ($visiteurs->isEmpty()) {
            return view('recherche', ['erreur' => 'Aucun visiteur trouvé']);
        }

        return view('resultats', compact('visiteurs'));
    }

    public function affecterRegion(Request $request, $idVisiteur)
    {
        \DB::table('travailler')->insert([
            'id_visiteur' => $idVisiteur,
            'id_region' => $request->id_region,
            'jjmmaa' => now(),
            'role_visiteur' => 'Visiteur'
        ]);

        return back()->with('success', 'Région affectée avec succès');
    }

    public function modifierRegion(Request $request, $idVisiteur)
    {
        \DB::table('travailler')
            ->where('id_visiteur', $idVisiteur)
            ->orderBy('jjmmaa', 'desc')
            ->limit(1)
            ->update([
                'id_region' => $request->id_region
            ]);

        return back()->with('success', 'Région modifiée avec succès');
    }

    public function supprimerAffectation($idVisiteur)
    {
        \DB::table('travailler')
            ->where('id_visiteur', $idVisiteur)
            ->delete();

        return back()->with('success', 'Affectation supprimée');
    }


    public function formAffectationRegion($idVisiteur)
    {
        $visiteur = DB::table('visiteur')
            ->where('id_visiteur', $idVisiteur)
            ->first();

        $regions = DB::table('region')->get();

        return view('formAffectationRegion', compact('visiteur', 'regions'));
    }


}
