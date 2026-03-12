@extends('layouts.master')

@section('content')
    <h2>Fiche du visiteur : {{ $visiteur->nom_visiteur }} {{ $visiteur->prenom_visiteur }}</h2>

    <div class="form-group mb-3">
        <button type="button" class="btn btn-primary"
                onclick="window.location='{{ url('/visiteur/'.$visiteur->id_visiteur.'/affecter-region') }}'">
            <i class="bi bi-plus-circle-fill"></i> Ajouter
        </button>

        <button type="button" class="btn btn-secondary"
                onclick="if (confirm('Annuler la saisie ?')) window.location='{{ url('/') }}'">
            <i class="bi bi-x-lg"></i> Retour
        </button>
    </div>

    <table class="table">
        <thead>
        <tr>
            <th>Date</th>
            <th>Région</th>
            <th>Secteur</th>
            <th colspan="2">Actions</th>
        </tr>
        </thead>

        <tbody>
        @foreach($regions as $r)
            <tr>
                {{-- Date d'affectation --}}
                <td>{{ $r->jjmmaa }}</td>

                {{-- Région --}}
                <td>{{ $r->nom_region }}</td>

                {{-- Secteur --}}
                <td>{{ $r->lib_secteur }}</td>

                {{-- Modifier --}}
                <td>
                    <a href="{{ url('/visiteur/'.$visiteur->id_visiteur.'/modifier-region/'.$r->id_region) }}"
                       class="btn">
                        <i class="bi bi-pencil"></i>
                    </a>
                </td>

                {{-- Supprimer --}}
                <td>
                    <a href="{{ url('/visiteur/'.$visiteur->id_visiteur.'/supprimer-region/'.$r->id_region) }}"
                       class="btn"
                       onclick="return confirm('Supprimer cette affectation ?')">
                        <i class="bi bi-trash"></i>
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
