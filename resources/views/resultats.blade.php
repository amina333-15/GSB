@extends('layouts.master')

@section('content')

    <h1>Résultats de la recherche</h1>

    <table class="table table-striped">
        <thead>
        <tr>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Laboratoire</th>
            <th>Secteur</th>
            <th>Région</th>
        </tr>
        </thead>
        <tbody>
        @foreach($visiteurs as $v)
            <tr>
                <td>{{ $v->nom_visiteur }}</td>
                <td>{{ $v->prenom_visiteur }}</td>
                <td>{{ $v->laboratoire->nom_laboratoire ?? '—' }}</td>
                <td>{{ $v->affectations->first()->secteur->lib_secteur ?? '—' }}</td>
                <td>{{ $v->affectations->first()->region->lib_region ?? '—' }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection
