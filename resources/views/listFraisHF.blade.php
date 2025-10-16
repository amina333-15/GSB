@extends('layouts.master')

@section('content')
    <div class="container">
        <h1> Frais Hors forfait de la fiche : </h1>
    </div>
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>Date</th>
            <th>Libellé</th><!--V5 p.7-->
            <th>Montant</th>
            <th><i class="bi bi-pencil"></i></th>
            <th><i class="bi bi-trash"></i></th>
        </tr>
        </thead>
        @foreach($listeHF as $fraisHF)
            <tr>
                    <td>{{ $fraisHF->date_fraishorsforfait }}</td>
                    <td>{{ $fraisHF->lib_fraishorsforfait }}</td>
                    <td>{{ $fraisHF->montant_fraishorsforfait}} €</td>


                <td><a href="{{url('/editerFraisHF/'.$fraisHF->id_fraishorsforfait)}}"><i class="bi bi-pencil"></i></a></td>
                <td><a href="{{url('/supprimerFraisHF/{idHF}'.$fraisHF->id_fraishorsforfait)}}"><i class="bi bi-trash"></i></a></td>
            </tr>
            <tr>
                <td colspan="2">Montant total</td>
                <td>{{$totalHF}}</td>
            </tr>
    </table>
    @endforeach
    <!----- Début partie validation--->
    <div class="form-group">
        <div class="col-md-12 col-md-offset-3">
            <button type="button" class="btn btn-primary"
                    onclick="window.location='{{ route('addFraisHF', ['id' => $frais->id_frais]) }}'">
                <i class="bi bi-plus-circle-fill"></i> Ajouter
            </button>


            <button type="button" class="btn btn-secondary"
                onclick="if (confirm('Annuler la saisie ?')) window.location='{{url('/')}}'">
        <i class="bi bi-x-lg"></i>
        Retour
    </button>
@endsection
