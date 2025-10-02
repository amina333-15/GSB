@extends('layouts.master')

@section('content')

    <form method="POST" action="{{ url('/validerFrais') }}">
        {{csrf_field() }}

        <input type="hidden" name="id" value="{{ $frais->id_frais }}">

        <h1>@if($frais->id_frais) Modification @else Ajout @endif Fiche de frais</h1>
        <div class="col-md-12 card card-body bg-light">
            <div class="form-group">
                <label class="col-md-3">Mois</label>
                <div class="col-md-6">
                    <input type="text" name="mois" class="form-control" maxlength="7" value="{{$frais->anneemois}}" placeholder="MM-AAAA" required>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-3">Montant saisi</label>
                <div class="col-md-6">
                    <input type="number" name="total" class="form-control " min="0" step="0.01" value="{{$frais->montantsasie}}" disabled>
                </div>
            </div>
            <!--------------------------------------------------------->
            <div class="col-md-12 col-md-offset-3">
                <a href="" class="btn btn-info disabled"@if(!$frais->id_frais) disabled @endif>Frais hors forfait</a>
                <a href="" class="btn btn-info disabled"@if(!$frais->id_frais) disabled @endif>Frais au forfait</a>
            </div>
            <!--------------------------------------------------------->

            <div class="form-group">
                <label class="col-md-3">Nb justificatifs</label>
                <div class="col-md-6">
                    <input type="number" name="nbjustif" class="form-control" min="0" value="{{$frais->nbjustificatifs}}">
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-3">Montant validé</label>
                <div class="col-md-6">
                    <input type="number" name="valide" class="form-control" min="0" step="0.01" value="{{$frais->montantvalide}}">
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-3">Etat</label>
                <div class="col-md-6">
                    <input type="number" name="etat" class="form-control" min="1" max="4" value="{{$frais->id_etat}}" required>
                </div>
            </div>
            <hr>

<!----- Début partie validation--->
                <div class="form-group">
                    <div class="col-md-12 col-md-offset-3">
                        <button type="submit" class="btn btn-primary">
                            Valider
                        </button>

                        <button type="button" class="btn btn-secondary"
                                onclick="if (confirm('Annuler la saisie ?')) window.location='{{url('/')}}'">
                            Annuler
                        </button>

                    </div>
                </div>
           </div>
    </form>

    @if(isset($erreur))
        <div class="alert alert-danger" role="alert">{{ $erreur }}</div>
    @endif
@endsection
