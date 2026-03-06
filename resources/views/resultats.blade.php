@extends('layouts.master')

@section('content')

    <h1>Résultats de la recherche</h1>

    <a href="{{ url('/rechercherVisiteur') }}" class="btn btn-secondary mb-3">
        ← Retour à la recherche
    </a>

    <table class="table table-striped">
        <thead>
        <tr>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Laboratoire</th>
            <th>Secteur</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($visiteurs as $v)
            <tr>
                <td>{{ $v->nom_visiteur }}</td>
                <td>{{ $v->prenom_visiteur }}</td>
                <td>{{ $v->nom_laboratoire ?? '—' }}</td>
                <td>{{ $v->lib_secteur ?? '—' }}</td>

                <td>
                    <a href="{{ url('/visiteur/'.$v->id_visiteur.'/affecter-region') }}"
                       class="btn btn-sm btn-primary">
                        Action
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection
