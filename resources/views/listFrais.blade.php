@extends('layouts.master')

@section('content')
    <div class="container">
        <title>Liste des fiches de frais</title>
    </div>
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>Mois</th>
            <th>Titre</th><!--V5 p.7-->
            <th>Modification</th><!--V5 p.7-->
            <th>Montant saisi</th>
            <th>Nb justificatifs</th>
            <th>Montant validé</th>
            <th>Etat</th>
            <th><i class="bi bi-pencil"></i></th>
        </tr>
        </thead>
        @foreach($fiches as $frais)
            <tr>
                <td>{{ $frais->anneemois }}</td>
                <td>{{ $frais->titre }}</td><!--V5 p.7-->
                <td>{{ $frais->datemodification }}</td><!--V5 p.7-->
                <td>{{ $frais->montantsaisi}} €</td>
                <td>{{ $frais->nbjustificatifs}}</td>
                <td>{{ $frais->montantvalide}} €</td>
                <td>{{ $frais->lib_etat}}</td><!--lib_etat-->

                <td><a href="{{url('/editerFrais/'.$frais->id_frais)}}"><i class="bi bi-pencil"></i></a></td>
            </tr>
        @endforeach
    </table>
@endsection
