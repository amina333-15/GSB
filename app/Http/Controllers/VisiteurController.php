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
use Illuminate\Support\Facades\DB;




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

        $visiteurs = Visiteur::query()
            ->join('laboratoire', 'visiteur.id_laboratoire', '=', 'laboratoire.id_laboratoire')
            ->join('secteur', 'visiteur.id_secteur', '=', 'secteur.id_secteur')
            ->join('travailler', 'visiteur.id_visiteur', '=', 'travailler.id_visiteur')
            ->join('region', 'travailler.id_region', '=', 'region.id_region')
            ->where('visiteur.nom_visiteur', 'like', "%$term%")
            ->orWhere('laboratoire.nom_laboratoire', 'like', "%$term%")
            ->orWhere('secteur.lib_secteur', 'like', "%$term%")
            ->select(
                'visiteur.id_visiteur',
                'visiteur.nom_visiteur',
                'visiteur.prenom_visiteur',
                'laboratoire.nom_laboratoire',
                'secteur.lib_secteur'
            )
            ->distinct()
            ->get();


        if ($visiteurs->isEmpty()) {
            return view('recherche', ['erreur' => 'Aucun visiteur trouvé']);
        }

        return view('resultats', compact('visiteurs'));
    }

    public function listRegion($id)
    {
        // Récupérer le visiteur
        $visiteur = DB::table('visiteur')
            ->where('id_visiteur', $id)
            ->first();

        // Récupérer les régions + secteurs du visiteur
        $regions = DB::table('travailler')
            ->join('region', 'travailler.id_region', '=', 'region.id_region')
            ->join('secteur', 'region.id_secteur', '=', 'secteur.id_secteur')
            ->where('travailler.id_visiteur', $id)
            ->orderBy('jjmmaa', 'desc')
            ->select(
                'region.nom_region',
                'secteur.lib_secteur',
                'travailler.jjmmaa',
                'travailler.role_visiteur',
                'region.id_region'
            )
            ->get();

        return view('listRegion', compact('visiteur', 'regions'));
    }

    public function formAffectationRegion($idVisiteur)
    {
        $visiteur = DB::table('visiteur')
            ->where('id_visiteur', $idVisiteur)
            ->first();

        $regions = DB::table('region')
            ->join('secteur', 'region.id_secteur', '=', 'secteur.id_secteur')
            ->select('region.id_region', 'region.nom_region', 'secteur.lib_secteur')
            ->get();

        return view('formAffectationRegion', [
            'visiteur' => $visiteur,
            'regions' => $regions,
            'mode' => 'ajout',
            'regionActuelle' => null
        ]);
    }


    public function affecterRegion(Request $request, $idVisiteur)
    {
        DB::table('travailler')->insert([
            'id_visiteur' => $idVisiteur,
            'id_region' => $request->id_region,
            'jjmmaa' => now(),
            'role_visiteur' => 'Visiteur'
        ]);

        return redirect('/visiteur/'.$idVisiteur.'/listRegion')
            ->with('success', 'Région affectée avec succès');
    }

    public function supprimerAffectation($idVisiteur)
    {
        DB::table('travailler')
            ->where('id_visiteur', $idVisiteur)
            ->delete();

        return redirect('/visiteur/'.$idVisiteur.'/listRegion')
            ->with('success', 'Affectation supprimée');
    }

    public function formModifierRegion($idVisiteur, $idRegion)
    {
        // Récupérer le visiteur
        $visiteur = DB::table('visiteur')
            ->where('id_visiteur', $idVisiteur)
            ->first();

        // Récupérer toutes les régions possibles
        $regions = DB::table('region')
            ->join('secteur', 'region.id_secteur', '=', 'secteur.id_secteur')
            ->select('region.id_region', 'region.nom_region', 'secteur.lib_secteur')
            ->get();

        // Récupérer l'affectation actuelle
        $regionActuelle = DB::table('travailler')
            ->where('id_visiteur', $idVisiteur)
            ->where('id_region', $idRegion)
            ->first();

        return view('formAffectationRegion', [
            'visiteur' => $visiteur,
            'regions' => $regions,
            'mode' => 'modif',
            'regionActuelle' => $regionActuelle
        ]);
    }

    public function modifierRegion(Request $request, $idVisiteur, $idRegion)
    {
        $affectation = DB::table('travailler')
            ->where('id_visiteur', $idVisiteur)
            ->where('id_region', $idRegion)
            ->first();

        DB::table('travailler')
            ->where('id_visiteur', $idVisiteur)
            ->where('id_region', $idRegion)
            ->where('jjmmaa', $affectation->jjmmaa)
            ->update([
                'id_region' => $request->id_region,
                'jjmmaa' => $request->jjmmaa
            ]);


        return redirect('/visiteur/'.$idVisiteur.'/listRegion')
            ->with('success', 'Affectation modifiée avec succès');
    }

    public function supprimerRegion($idVisiteur, $idRegion)
    {
        DB::table('travailler')
            ->where('id_visiteur', $idVisiteur)
            ->where('id_region', $idRegion)
            ->delete();

        return redirect('/visiteur/'.$idVisiteur.'/listRegion')
            ->with('success', 'Affectation supprimée');
    }

    public function visiteursParRegion($idRegion)
    {
        $region = DB::table('region')
            ->where('id_region', $idRegion)
            ->first();

        $visiteurs = DB::table('visiteur')
            ->join('travailler', 'visiteur.id_visiteur', '=', 'travailler.id_visiteur')
            ->join('region', 'travailler.id_region', '=', 'region.id_region')
            ->join('laboratoire', 'visiteur.id_laboratoire', '=', 'laboratoire.id_laboratoire')
            ->join('secteur', 'visiteur.id_secteur', '=', 'secteur.id_secteur')
            ->where('travailler.id_region', $idRegion)
            ->select(
                'visiteur.*',
                'laboratoire.nom_laboratoire',
                'secteur.lib_secteur',
                'region.nom_region'
            )
            ->get();


        return view('visiteursParRegion', compact('region', 'visiteurs'));
    }

    public function choisirRegion()
    {
        $regions = DB::table('region')->get();
        return view('choisirRegion', compact('regions'));
    }

    public function top10Laboratoires()
    {
        $top10 = DB::table('activite_compl')
            ->join('realiser', 'activite_compl.id_activite_compl', '=', 'realiser.id_activite_compl')
            ->join('visiteur', 'realiser.id_visiteur', '=', 'visiteur.id_visiteur')
            ->join('laboratoire', 'visiteur.id_laboratoire', '=', 'laboratoire.id_laboratoire')
            ->select(
                'laboratoire.nom_laboratoire',
                DB::raw('COUNT(activite_compl.id_activite_compl) as total_activites')
            )
            ->groupBy('laboratoire.nom_laboratoire')
            ->orderByDesc('total_activites')
            ->limit(10)
            ->get();

        return view('top10Laboratoires', compact('top10'));
    }

}
