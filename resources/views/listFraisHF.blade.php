@extends('layouts.master')

@section('content')
    <div class="container">
        <h1> Frais Hors forfait de la fiche : </h1>
    </div>

    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>Date</th>
            <th>Libellé</th>
            <th>Montant</th>
            <th><i class="bi bi-pencil"></i></th>
            <th><i class="bi bi-trash"></i></th>
        </tr>
        </thead>

        @foreach($listeHF as $fraisHF)
            <tr>
                <td>{{ $fraisHF->date_fraishorsforfait }}</td>
                <td>{{ $fraisHF->lib_fraishorsforfait }}</td>
                <td>{{ $fraisHF->montant_fraishorsforfait }} €</td>

                <td>
                    <a href="{{ route('editFraisHF', ['idHF' => $fraisHF->id_fraishorsforfait]) }}">
                        <i class="bi bi-pencil"></i>
                    </a>
                </td>

                <td>
                    <a href="{{ route('removeFraisHF', ['idHF' => $fraisHF->id_fraishorsforfait]) }}"
                       onclick="return confirm('Supprimer cette fiche de frais ?')">
                        <i class="bi bi-trash"></i>
                    </a>
                </td>
            </tr>
        @endforeach

        <tr>
            <td colspan="2"><strong>Montant total</strong></td>
            <td><strong>{{ $totalHF }} €</strong></td>
        </tr>
    </table>

    <div class="form-group">
        <div class="col-md-12 col-md-offset-3">

            <button type="button" class="btn btn-primary"
                    onclick="window.location='{{ route('addFraisHF', ['id' => $frais->id_frais]) }}'">
                <i class="bi bi-plus-circle-fill"></i> Ajouter
            </button>

            <button type="button" class="btn btn-secondary"
                    onclick="window.location='{{ url('/') }}'">
                <i class="bi bi-x-lg"></i> Retour
            </button>

        </div>
    </div>
@endsection
