@extends('layouts.master')

@section('content')

    <form method="POST" action="{{ url('/validerFraisHF') }}">
        @csrf

        <!-- ID du frais principal -->
        <input type="hidden" name="id_frais" value="{{ $fraisHF->id_frais }}">

        <!-- ID du frais hors forfait (uniquement en modification) -->
        @if($fraisHF->id_fraishorsforfait)
            <input type="hidden" name="id_fraishorsforfait" value="{{ $fraisHF->id_fraishorsforfait }}">
        @endif

        <h1>{{ $fraisHF->id_fraishorsforfait ? 'Modification' : 'Ajout' }} Fiche de Frais Hors Forfait</h1>

        <div class="form-group">
            <label class="col-md-3">Date</label>
            <div class="col-md-6">
                <input type="date" name="date" class="form-control"
                       value="{{ $fraisHF->date_fraishorsforfait }}" required>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-3">Libellé</label>
            <div class="col-md-6">
                <input type="text" name="libelle" class="form-control"
                       value="{{ $fraisHF->lib_fraishorsforfait }}" required>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-3">Montant</label>
            <div class="col-md-6">
                <input type="number" name="montant" class="form-control"
                       min="0" step="0.01"
                       value="{{ $fraisHF->montant_fraishorsforfait }}" required>
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-12 col-md-offset-3">

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check2"></i> Valider
                </button>

                <button type="button" class="btn btn-secondary"
                        onclick="if (confirm('Annuler la saisie ?')) window.location='{{ url('/') }}'">
                    <i class="bi bi-x-lg"></i> Annuler
                </button>

                @if($fraisHF->id_fraishorsforfait)
                    <a href="{{ url('/supprimerFraisHF/'.$fraisHF->id_fraishorsforfait) }}"
                       class="btn btn-danger"
                       onclick="return confirm('Supprimer cette fiche de frais ?')">
                        Supprimer
                    </a>
                @endif

            </div>
        </div>

    </form>

    @if(isset($erreur))
        <div class="alert alert-danger" role="alert">{{ $erreur }}</div>
    @endif

@endsection
