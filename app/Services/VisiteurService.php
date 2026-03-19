<?php

namespace App\Services;

use App\Exceptions\UserException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Session;
use App\Models\Visiteur;
use Illuminate\Support\Facades\DB;



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

    public function search($term)
    {
        try {
            return Visiteur::query()
                ->join('laboratoire', 'visiteur.id_laboratoire', '=', 'laboratoire.id_laboratoire')
                ->join('secteur', 'visiteur.id_secteur', '=', 'secteur.id_secteur')
                ->join('travailler', 'visiteur.id_visiteur', '=', 'travailler.id_visiteur')
                ->join('region', 'travailler.id_region', '=', 'region.id_region')
                ->select(
                    'visiteur.id_visiteur',
                    'visiteur.nom_visiteur',
                    'visiteur.prenom_visiteur',
                    'laboratoire.nom_laboratoire',
                    'secteur.lib_secteur'
                )
                ->select(
                    'visiteur.id_visiteur',
                    'visiteur.nom_visiteur',
                    'visiteur.prenom_visiteur',
                    'laboratoire.nom_laboratoire',
                    'secteur.lib_secteur'
                )
                ->distinct()
                ->get();

        } catch (QueryException $e) {
            throw new \Exception("Erreur lors de la recherche : " . $e->getMessage());
        }
    }

    public function getVisiteur($id)
    {
        return DB::table('visiteur')
            ->where('id_visiteur', $id)
            ->first();
    }

    public function getRegionsVisiteur($id)
    {
        return DB::table('travailler')
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
    }

    public function getAllRegions()
    {
        return DB::table('region')
            ->join('secteur', 'region.id_secteur', '=', 'secteur.id_secteur')
            ->select('region.id_region', 'region.nom_region', 'secteur.lib_secteur')
            ->get();
    }

    public function affecterRegion($idVisiteur, $idRegion)
    {
        DB::table('travailler')->insert([
            'id_visiteur' => $idVisiteur,
            'id_region' => $idRegion,
            'jjmmaa' => now(),
            'role_visiteur' => 'Visiteur'
        ]);
    }

    public function supprimerAffectation($idVisiteur)
    {
        DB::table('travailler')
            ->where('id_visiteur', $idVisiteur)
            ->delete();
    }

    public function getAffectation($idVisiteur, $idRegion)
    {
        return DB::table('travailler')
            ->where('id_visiteur', $idVisiteur)
            ->where('id_region', $idRegion)
            ->first();
    }

    public function modifierRegion($idVisiteur, $idRegion, $newRegion, $newDate)
    {
        $affectation = $this->getAffectation($idVisiteur, $idRegion);

        DB::table('travailler')
            ->where('id_visiteur', $idVisiteur)
            ->where('id_region', $idRegion)
            ->where('jjmmaa', $affectation->jjmmaa)
            ->update([
                'id_region' => $newRegion,
                'jjmmaa' => $newDate
            ]);
    }

    public function supprimerRegion($idVisiteur, $idRegion)
    {
        DB::table('travailler')
            ->where('id_visiteur', $idVisiteur)
            ->where('id_region', $idRegion)
            ->delete();
    }

    public function getRegion($idRegion)
    {
        return DB::table('region')
            ->where('id_region', $idRegion)
            ->first();
    }

    public function getVisiteursParRegion($idRegion)
    {
        return DB::table('visiteur')
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
    }

    public function top10Laboratoires()
    {
        return DB::table('activite_compl')
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
    }
}
