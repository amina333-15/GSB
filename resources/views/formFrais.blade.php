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
                <label class="col-md-3">Titre</label>
                <div class="col-md-6">
                    <input type="text" name="titre" value="{{ $frais->titre }}" class="form-control" required>
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-3">Modification</label>
                <div class="col-md-6">
                    <input type="date" name="datemodification" class="form-control"
                           value="{{ ($frais->datemodification)->format('Y-m-d') }}"
                           required>
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
                <label class="col-md-3">État</label>
                <div class="col-md-6">
                    <select name="id_etat" class="form-control">
                        @foreach($etats as $etat)
                            <option value="{{ $etat->id_etat }}"
                                {{ $etat->id_etat == $frais->id_etat ? 'selected' : '' }}>
                                {{ $etat->lib_etat }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>



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

                    @if(isset($frais) && $frais->id_frais)
                        <a href="{{ url('/supprimerFrais/'.$frais->id_frais) }}"
                           id="suppr" class="btn btn-danger"
                           onclick="return confirm('Supprimer cette fiche de frais ?')">
                            Supprimer
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </form>

    @if(isset($erreur))
        <div class="alert alert-danger" role="alert">{{ $erreur }}</div>
    @endif
@endsection
