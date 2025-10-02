@extends('layouts.master')

@section('content')
    <div class="container">
        <title>Liste des fiches de frais</title>
    </div>
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>Mois</th>
            <th>Montant saisi</th>
            <th>Nb justificatifs</th>
            <th>Montant validé</th>
            <th>Etat</th>
            <th>Modifier</th>
        </tr>
        </thead>
        @foreach($fiches as $frais)
            <tr>
                <td>{{ $frais->anneemois }}</td>
                <td>{{ $frais->montantsaisi}} €</td>
                <td>{{ $frais->nbjustificatifs}}</td>
                <td>{{ $frais->montantvalide}} €</td>
                <td>{{ $frais->id_etat }}</td>

                <td><a href="{{url('/editerFrais/'.$frais->id_frais)}}">Modifier</a></td>
            </tr>
        @endforeach
    </table>
@endsection
