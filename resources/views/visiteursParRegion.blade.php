@extends('layouts.master')

@section('content')

    <h1>Visiteurs de la région : {{ $region->nom_region }}</h1>

    <a href="{{ url('/rechercherVisiteur') }}" class="btn btn-secondary mb-3">
        ← Retour
    </a>

    <table class="table table-striped">
        <thead>
        <tr>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Laboratoire</th>
            <th>Secteur</th>
            <th>Région</th>
            <th>Actions</th>
        </tr>
        </thead>

        <tbody>
        @foreach($visiteurs as $v)
            <tr>
                <td>{{ $v->nom_visiteur }}</td>
                <td>{{ $v->prenom_visiteur }}</td>
                <td>{{ $v->nom_laboratoire ?? '—' }}</td>

                {{-- Secteur --}}
                <td>{{ $v->lib_secteur ?? '—' }}</td>

                {{-- Région --}}
                <td>{{ $v->nom_region ?? '—' }}</td>

                <td>
                    <a href="{{ url('/visiteur/'.$v->id_visiteur.'/listRegion') }}"
                       class="btn btn-sm btn-primary">
                        Voir affectations
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection
