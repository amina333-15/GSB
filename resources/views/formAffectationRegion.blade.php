@extends('layouts.master')

@section('content')

    <h2>
        @if($mode === 'modif')
            Modification d'une
        @else
            Nouvelle
        @endif
        affectation pour : {{ $visiteur->prenom_visiteur }} {{ $visiteur->nom_visiteur }}
    </h2>

    <form method="POST"
          action="@if($mode === 'modif')
                    {{ url('/visiteur/'.$visiteur->id_visiteur.'/modifier-region/'.$regionActuelle->id_region) }}
                  @else
                    {{ url('/visiteur/'.$visiteur->id_visiteur.'/affecter-region') }}
                  @endif">
        @csrf

        <div class="mb-3">
            <label class="form-label">Choisir une région</label>
            <select name="id_region" class="form-control" required>
                @foreach($regions as $r)
                    <option value="{{ $r->id_region }}"
                            @if($mode === 'modif' && $r->id_region == $regionActuelle->id_region) selected @endif>
                        {{ $r->nom_region }} — Secteur : {{ $r->lib_secteur }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Champ Date --}}
        <div class="mb-3">
            <label class="form-label">Date d'affectation</label>
            <input type="date" name="jjmmaa" class="form-control"
                   value="@if($mode === 'modif') {{ $regionActuelle->jjmmaa }} @endif"
                   required>
        </div>

        <button type="submit" class="btn btn-primary">
            @if($mode === 'modif') Modifier @else Valider @endif
        </button>

        @if($mode === 'modif')
            <a href="{{ url('/visiteur/'.$visiteur->id_visiteur.'/supprimer-region/'.$regionActuelle->id_region) }}"
               class="btn btn-danger"
               onclick="return confirm('Supprimer cette affectation ?')">
                Supprimer
            </a>
        @endif

        <a href="{{ url('/visiteur/'.$visiteur->id_visiteur.'/listRegion') }}"
           class="btn btn-secondary">
            Annuler
        </a>
    </form>

@endsection
