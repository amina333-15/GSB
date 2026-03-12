@extends('layouts.master')

@section('content')

    <h1>Top 10 des laboratoires les plus actifs</h1>

    <a href="{{ url('/') }}" class="btn btn-secondary mb-3">
        ← Retour à l'accueil
    </a>

    <table class="table table-striped">
        <thead>
        <tr>
            <th>Laboratoire</th>
            <th>Nombre d'activités complémentaires</th>
        </tr>
        </thead>

        <tbody>
        @foreach($top10 as $labo)
            <tr>
                <td>{{ $labo->nom_laboratoire }}</td>
                <td>{{ $labo->total_activites }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection
