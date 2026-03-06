@extends('layouts.master')

@section('content')

    <h1>Affectation d'une région</h1>

    <h4>{{ $visiteur->prenom_visiteur }} {{ $visiteur->nom_visiteur }}</h4>

    <form method="POST" action="{{ url('/visiteur/'.$visiteur->id_visiteur.'/affecter-region') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label">Choisir une région</label>
            <select name="id_region" class="form-control">
                @foreach($regions as $r)
                    <option value="{{ $r->id_region }}">{{ $r->nom_region }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">
            Affecter / Modifier
        </button>

        <a href="{{ url('/visiteur/'.$visiteur->id_visiteur.'/supprimer-affectation') }}"
           class="btn btn-danger"
           onclick="return confirm('Supprimer l’affectation ?')">
            Supprimer
        </a>

        <a href="{{ url('/rechercherVisiteur') }}" class="btn btn-secondary">
            Annuler
        </a>
    </form>

@endsection
